<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Tasks;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Gremlin\Query;

class Project implements Tasks {
    private $project_dir = '.';
    private $executable = 'exakat';
    
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70',
                              'Appinfo', '"Dead code"', 'Security', 'Custom',
                              'Analyze');

    protected $reports = array('Premier' => array(//'Markdown' => 'report',
                                                  //'Sqlite'   => 'report',
                                                  'Devoops'  => 'report',
                                                  //'Html'     => 'report',
                                                  //'Text'     => 'report'
                                                  ),
                               'Counts'  => array('Sqlite'   => 'counts'));
    
    public function run(\Config $config) {
        $project = $config->project;

        $this->project_dir = $config->projects_root.'/projects/'.$project;

        if ($config->is_phar) {
            $this->executable = basename(dirname(dirname(__DIR__)));
        } else {
            $this->executable = $_SERVER['SCRIPT_NAME'];
        }

        if ($config->project === 'default') {
            die("Usage : php {$this->executable} project -p [Project name]\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project)) {
            die("Project '$project' doesn't exist in projects folder. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
            die("Project '$project' exists but has no config file. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/code')) {
            die("Project '$project' exists but has no code folder. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/log')) {
            die("Project '$project' exists but has no log folder. Aborting\n");
        }

        // cleaning log directory (possibly logs)
        $logs = glob($config->projects_root.'/projects/'.$project.'/log/*');
        foreach($logs as $log) {
            unlink($log);
        }

        $this->logTime('Start');

        $datastorePath = $config->projects_root.'/projects/'.$config->project.'/datastore.sqlite';
        if (file_exists($datastorePath)) {
            unlink($datastorePath);
        }
        
        // cleaning datastore
        $datastore = new \Datastore($config);
        $datastore->cleanTable('hash');
        $audit_start = time();
        $datastore->addRow('hash', array('audit_start' => $audit_start,
                                         'exakat_version' => \Exakat::VERSION,
                                         'exakat_build' => \Exakat::BUILD,
                                         ));

        $thread = new \Thread();
        display("Running project '$project'\n");

        display("Cleaning DB\n");
        shell_exec('php '.$this->executable.' cleandb -v');
        $this->logTime('Files');

        display("Running files\n");
        shell_exec('php '.$this->executable.' files -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/files.final.log');
        $this->logTime('Files');
        display("Loading project\n");

        $thread->waitForAll();
        display("waited For All\n");

        shell_exec('php '.$this->executable.' load -r -d '.$config->projects_root.'/projects/'.$project.'/code/ -p '.$project);
        display("Project loaded\n");
        $this->logTime('Loading');

        $res = shell_exec('php '.$this->executable.' build_root -v -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/build_root.final.log');
        display("Build root\n");
        $this->logTime('Build_root');

        $res = shell_exec('php '.$this->executable.' tokenizer -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log');
        if (!empty($res) && strpos('javax.script.ScriptException', $res) !== false) {
            file_put_contents($config->projects_root.'/log/tokenizer_error.log', $res);
            die();
        }

        $this->logTime('Tokenizer');
        display("Project tokenized\n");

        $thread->run('php '.$this->executable.' magicnumber -p '.$project);

        $thread->run('php '.$this->executable.' errors > '.$config->projects_root.'/projects/'.$project.'/log/errors.log');
        display("Got the errors (if any)\n");

        $thread->run('php '.$this->executable.' stat -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/stat.log');
        display("Stats\n");

        $thread->run('php '.$this->executable.' log2csv -p '.$project);

        $this->logTime('Stats');

        $processes = array();
        foreach($this->themes as $theme) {
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));
            shell_exec('php '.$this->executable.' analyze -norefresh -p '.$project.' -T '.$theme.' > '.$config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log;
mv '.$config->projects_root.'/projects/'.$project.'/log/analyze.log '.$config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.log');
            display("Analyzing $theme\n");
        }

        display("Project analyzed\n");
        $this->logTime('Analyze');

        $oldConfig = \Config::factory();
        foreach($this->reports as $reportName => $formats) {
            foreach($formats as $format => $fileName) {
                display("Reporting $reportName in $format\n");
                $args = array ( 1 => 'report',
                                2 => '-p',
                                3 => $config->project,
                                4 => '-f',
                                5 => $fileName,
                                6 => '-format',
                                7 => $format,
                                8 => '-report',
                                9 => $reportName,
                                );
                $config = \Config::factory($args);
            
                $report = new Report();
                $report->run($config);
                unset($report);
            }
        }
        \Config::factory($oldConfig);

        display("Project reported\n");

        shell_exec('php '.$this->executable.' stat > '.$config->projects_root.'/projects/'.$project.'/log/stat.log');
        display("Stats 2\n");
        
        $audit_end = time();
        $datastore->addRow('hash', array('audit_end'    => $audit_end,
                                         'audit_length' => $audit_end - $audit_start));

        shell_exec('php '.$this->executable.' results -P Structures/EchoWithConcat -json -f '.$config->projects_root.'/projects/'.$project.'/EchoWithConcat');
        shell_exec('php '.$this->executable.' results -P Functions/IsExtFunction   -json -f '.$config->projects_root.'/projects/'.$project.'/PhpFunctions');
        $this->logTime('Final');
        display("End 2\n");
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen($this->project_dir.'/log/project.timing.csv', 'w+');
        }
        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }
}

?>
