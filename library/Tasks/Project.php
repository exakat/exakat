<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Project extends Tasks {
    private $project_dir = '.';
    private $config = null;
    
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70',
                              'Appinfo', 'Appcontent', '"Dead code"', 'Security', 'Custom',
                              'Analyze');

    protected $reports = array('Premier' => array('Devoops' => 'report',
                                                  'Faceted' => 'faceted'));
    
    const TOTAL_STEPS = 23; // 2 Reports + 10 Analyzes + 10 other steps

    public function run(\Config $config) {
        $this->config = $config;
        
        $progress = 0;

        $project = $config->project;

        $this->project_dir = $config->projects_root.'/projects/'.$project;

        if ($config->project === 'default') {
            die("Usage : {$config->php} {$config->executable} project -p [Project name]\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project)) {
            die("Project '$project' doesn't exist in projects folder. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
            die("Project '$project' exists but has no config file. Aborting\n");
        }

        if (!file_exists($config->codePath)) {
            die("Project '$project' exists but has no code folder ($config->codePath). Aborting\n");
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

        // cleaning datastore
        $this->datastore = new \Datastore($config, \Datastore::CREATE);
        
        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start' => $audit_start,
                                               'exakat_version' => \Exakat::VERSION,
                                               'exakat_build' => \Exakat::BUILD,
                                         ));

        display("Running project '$project'\n");

        display("Cleaning DB\n");
        $analyze = new CleanDb($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('CleanDb');
        $this->updateProgress($progress++);

        display("Search for external libraries\n");
        $args = array ( 1 => 'findextlib',
                        2 => '-p',
                        3 => $config->project,
                        4 => '-u',
                        );
        
        $configThema = \Config::push($args);

        $analyze = new FindExternalLibraries($this->gremlin);
        $analyze->run($configThema);
        unset($report);
        $this->updateProgress($progress++);

        \Config::pop();
        unset($analyze);
        $this->updateProgress($progress++);

        display("Running files\n");
        $analyze = new Files($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('Files');
        $this->updateProgress($progress++);

        $this->checkTokenLimit();

        $analyze = new Load($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        display("Project loaded\n");
        $this->logTime('Loading');
        $this->updateProgress($progress++);

        $analyze = new Build_root($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        display("Build root\n");
        $this->logTime('Build_root');
        $this->updateProgress($progress++);

        $analyze = new Tokenizer($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('Tokenizer');
        display("Project tokenized\n");
        $this->updateProgress($progress++);

        $analyze = new Magicnumber($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->updateProgress($progress++);

        $analyze = new Errors($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        display("Got the errors (if any)\n");
        $this->updateProgress($progress++);

        $analyze = new Log2csv($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('Stats');

        // Dump is a child process
        exec($config->php . ' '.$config->executable.' dump -p '.$config->project.'   > /dev/null &');
        display('Started dump process');

        foreach($this->themes as $theme) {
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));

            $args = array ( 1 => 'analyze',
                            2 => '-p',
                            3 => $config->project,
                            4 => '-T',
                            5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                            6 => '-norefresh',
                            );
            
            try {
                $configThema = \Config::push($args);

                $analyze = new Analyze($this->gremlin);
                $analyze->run($configThema);
                unset($report);
                
                rename($config->projects_root.'/projects/'.$project.'/log/analyze.log', $config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.log');
                $this->updateProgress($progress++);

                \Config::pop();
            } catch (\Exception $e) {
                echo "Error while running the Analyze $theme \n",
                     $e->getMessage();
                file_put_contents($config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log', $e->getMessage());
                die();
            }
        }

        display("Analyzed project\n");
        $this->updateProgress($progress++);
        $this->logTime('Analyze');

/*
        check on dump ? 
*/

        $this->updateProgress($progress++);
        $this->logTime('Analyze');

        $oldConfig = \Config::factory();
        foreach($this->reports as $reportName => $formats) {
            foreach($formats as $format => $fileName) {
                display("Reporting $reportName in $format\n");
                $this->updateProgress($progress++);
                
                $args = array ( 1 => 'report',
                                2 => '-p',
                                3 => $config->project,
                                4 => '-file',
                                5 => $fileName,
                                6 => '-format',
                                7 => $format,
                                );
                $config = \Config::factory($args);
            
                try {
                    $report = new Report2($this->gremlin);
                    $report->run($config);
                    unset($report);
                } catch (\Exception $e) {
                    echo "Error while building $reportName in $format \n",
                         $e->getMessage();
                    die();
                }
            }
        }
        \Config::factory($oldConfig);

        display("Reported project\n");

        $analyze = new Stat($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        display("Stats\n");
        
        $audit_end = time();
        
        // measure Neo4j's final size
        $res = shell_exec('du -sh '.$config->neo4j_folder);
        $neo4jSize = trim(str_replace(basename($config->neo4j_folder), '', $res));

        $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                               'audit_length' => $audit_end - $audit_start,
                                               'neo4jSize'    => $neo4jSize));
                                               

        $this->logTime('Final');
        display("End 2\n");
        $this->updateProgress($progress++);
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

    private function updateProgress($status) {
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->progress = number_format(100 * $status / self::TOTAL_STEPS, 0);
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));
    }

}

?>
