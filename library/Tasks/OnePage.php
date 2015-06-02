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

class OnePage implements Tasks {
    private $project_dir = '.';
    private $executable = 'exakat';
    
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70',
                              'OneFile');

    protected $reports = array('OnePage' => array('Json'   => 'report'));
    
    public function run(\Config $config) {
        $begin = microtime(true);
        $project = 'onepage';
        $this->project_dir = $config->projects_root.'/projects/'.$project;

        if ($config->is_phar) {
            $this->executable = basename(dirname(dirname(__DIR__)));
        } else {
            $this->executable = $_SERVER['SCRIPT_NAME'];
        }

        // todo : check that there is indeed this project or create it.
        
        copy($config->filename, $config->projects_root.'/projects/'.$project.'/code/onepage.php');
        $this->reports['OnePage']['Json'] = md5_file($config->filename);
        
        $this->cleanLog($config->projects_root.'/projects/'.$project.'/log/');
        $this->logTime('Start');

        $datastorePath = $config->projects_root.'/projects/'.$project.'/datastore.sqlite';
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
// cleaning should be done after, not initialy
        shell_exec('php '.$this->executable.' cleandb -v');
        $this->logTime('Files');

        display("Running files\n");
        shell_exec('php '.$this->executable.' files -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/files.final.log');
        $this->logTime('Files');
        display("Loading project\n");

        $thread->waitForAll();
        display("waited For All\n");

        shell_exec('php '.$this->executable.' load -v -r -d '.$config->projects_root.'/projects/'.$project.'/code/ -p '.$project. ' > '.$config->projects_root.'/projects/'.$project.'/log/load.final.log' );
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
                display("Reporting $reportName in $format in file $fileName\n");
                $args = array ( 1 => 'report',
                                2 => '-p',
                                3 => $project,
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

        $audit_end = time();
        $datastore->addRow('hash', array('audit_end'    => $audit_end,
                                         'audit_length' => $audit_end - $audit_start));

        $this->logTime('Final');
        display("End 2\n");
        $end = microtime(true);
        display("Total time : ".number_format(($end - $begin), 2)."s\n");
        
        $this->cleanLog($config->projects_root.'/projects/'.$project.'/log/');
    }

    private function cleanLog($path) {
        // cleaning log directory (possibly logs)
        $logs = glob("$path/*");
        foreach($logs as $log) {
            unlink($log);
        }
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
