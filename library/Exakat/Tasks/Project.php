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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exakat;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\NoSuchProject;

class Project extends Tasks {
    const CONCURENCE = self::NONE;
    
    private $project_dir = '.';
    
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70', 'CompatibilityPHP71',
                              'Appinfo', 'Appcontent', '"Dead code"', 'Security', 'Custom',
                              'Analyze');

    protected $reports = array('Premier' => array('Ambassador' => 'report',
                                                  'Faceted'    => 'faceted'));
    
    const TOTAL_STEPS = 23; // 2 Reports + 10 Analyzes + 10 other steps

    public function run(Config $config) {
        $this->config = $config;
        
        $progress = 0;

        $project = $config->project;

        $this->project_dir = $config->projects_root.'/projects/'.$project;

        if ($config->project == "default") {
            throw new ProjectNeeded();
        }

        if (!file_exists($config->projects_root.'/projects/'.$project)) {
            throw new NoSuchProject($config->project);
        }

        // cleaning log directory (possibly logs)
        $logs = glob($config->projects_root.'/projects/'.$project.'/log/*');
        foreach($logs as $log) {
            unlink($log);
        }

        $this->logTime('Start');
        $this->addSnitch(array('step' => 'Start', 'project' => $config->project));

        // cleaning datastore
        $this->datastore = new Datastore($config, Datastore::CREATE);
        
        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start' => $audit_start,
                                               'exakat_version' => Exakat::VERSION,
                                               'exakat_build' => Exakat::BUILD,
                                         ));

        display("Running project '$project'\n");

        display("Cleaning DB\n");
        $analyze = new CleanDb($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('CleanDb');
        $this->addSnitch(array('step' => 'Clean DB', 'project' => $config->project));
        $this->updateProgress($progress++);

        display("Search for external libraries\n");
        $args = array ( 1 => 'findextlib',
                        2 => '-p',
                        3 => $config->project,
                        4 => '-u',
                        );
        
        $configThema = Config::push($args);

        $analyze = new FindExternalLibraries($this->gremlin);
        $analyze->run($configThema);
        unset($report);
        $this->updateProgress($progress++);
        $this->addSnitch(array('step' => 'External lib', 'project' => $config->project));

        Config::pop();
        unset($analyze);
        $this->updateProgress($progress++);

        display("Running files\n");
        $analyze = new Files($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('Files');
        $this->updateProgress($progress++);
        $this->addSnitch(array('step' => 'Files', 'project' => $config->project));

        $this->checkTokenLimit();

        $analyze = new Load($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        display("Project loaded\n");
        $this->logTime('Loading');
        $this->updateProgress($progress++);

        // paralell running
        exec($config->php . ' '.$config->executable.' magicnumber -p '.$config->project.'   > /dev/null &');
        $this->addSnitch(array('step' => 'Magic Numbers', 'project' => $config->project));

        // Dump is a child process
        exec($config->php . ' '.$config->executable.' dump -p '.$config->project.'   > /dev/null &');
        display('Started dump process');

        foreach($this->themes as $theme) {
            $this->addSnitch(array('step' => 'Analyze : '.$theme, 'project' => $config->project));
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));

            $args = array ( 1 => 'analyze',
                            2 => '-p',
                            3 => $config->project,
                            4 => '-T',
                            5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                            6 => '-norefresh',
                            );
            
            try {
                $configThema = Config::push($args);

                $analyze = new Analyze($this->gremlin);
                $analyze->run($configThema);
                unset($report);
                
                rename($config->projects_root.'/projects/'.$project.'/log/analyze.log', $config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.log');
                $this->updateProgress($progress++);

                Config::pop();
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
        $this->addSnitch(array('step' => 'Analyzed', 'project' => $config->project));

/*
        check on dump ? 
*/

        $this->updateProgress($progress++);
        $this->logTime('Analyze');

        $oldConfig = Config::factory();
        foreach($this->reports as $reportName => $formats) {
            foreach($formats as $format => $fileName) {
                display("Reporting $reportName in $format\n");
                $this->addSnitch(array('step' => 'Report : '.$format, 'project' => $config->project));
                $this->updateProgress($progress++);
                
                $args = array ( 1 => 'report',
                                2 => '-p',
                                3 => $config->project,
                                4 => '-file',
                                5 => $fileName,
                                6 => '-format',
                                7 => $format,
                                );
                $config = Config::factory($args);
            
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

        Config::factory($oldConfig);
        display("Reported project\n");

        $audit_end = time();
        
        // measure Neo4j's final size
        $res = shell_exec('du -sh '.$config->neo4j_folder.' 2>/dev/null');
        $neo4jSize = trim(str_replace(basename($config->neo4j_folder), '', $res));

        $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                               'audit_length' => $audit_end - $audit_start,
                                               'neo4jSize'    => $neo4jSize));
                                               

        $this->logTime('Final');
        $this->removeSnitch();
        display("End\n");
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
