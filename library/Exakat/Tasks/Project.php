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
                              'Analyze', 'Preferences',
                              'Appinfo', 'Appcontent', '"Dead code"', 'Security', 'Custom',
                              );

    protected $reports = array('Premier' => array('Ambassador' => 'report',
                                                  'Devoops'    => 'oldreport'));
    
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
        $this->addSnitch(array('step'    => 'Start', 
                               'project' => $config->project));

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
        $this->addSnitch(array('step'    => 'Clean DB', 
                               'project' => $config->project));

        display("Search for external libraries\n");
        $args = array ( 1 => 'findextlib',
                        2 => '-p',
                        3 => $config->project,
                        4 => '-u',
                        );
        
        $configThema = Config::push($args);

        $analyze = new FindExternalLibraries($this->gremlin);
        $analyze->run($configThema);

        $this->addSnitch(array('step'   => 'External lib', 
                              'project' => $config->project));

        Config::pop();
        unset($analyze);

        display("Running files\n");
        $analyze = new Files($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        $this->logTime('Files');
        $this->addSnitch(array('step'    => 'Files', 
                               'project' => $config->project));

        $this->checkTokenLimit();

        $analyze = new Load($this->gremlin);
        $analyze->run($config);
        unset($analyze);
        display("Project loaded\n");
        $this->logTime('Loading');

        // paralell running
        exec($config->php . ' '.$config->executable.' magicnumber -p '.$config->project.'   > /dev/null &');
        $this->addSnitch(array('step'    => 'Magic Numbers', 
                               'project' => $config->project));

        // Dump is a child process
        shell_exec($config->php . ' '.$config->executable.' dump -p '.$config->project);

        foreach($this->themes as $theme) {
            $this->addSnitch(array('step' => 'Analyze : '.$theme, 'project' => $config->project));
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));

            $args = array ( 1 => 'analyze',
                            2 => '-p',
                            3 => $config->project,
                            4 => '-T',
                            5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                            6 => '-norefresh',
                            7 => '-u'
                            );
            
            try {
                $configThema = Config::push($args);

                $analyze = new Analyze($this->gremlin);
                $analyze->run($configThema);
                unset($analyze);
                
                rename($config->projects_root.'/projects/'.$project.'/log/analyze.log', $config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.log');

                Config::pop();

                $args = array ( 1 => 'dump',
                                2 => '-p',
                                3 => $config->project,
                                4 => '-T',
                                5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                                6 => '-u'
                            );

                $configThema = Config::push($args);

                $dump = new Dump($this->gremlin);
                $dump->run($configThema);
                unset($dump);

                Config::pop();
            } catch (\Exception $e) {
                echo "Error while running the Analyze $theme \n",
                     $e->getMessage(),
                     "\nTrying next analysis\n";
                file_put_contents($config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log', $e->getMessage());
            }
        }

        display("Analyzed project\n");
        $this->logTime('Analyze');
        $this->addSnitch(array('step' => 'Analyzed', 'project' => $config->project));

        $this->logTime('Analyze');

        $oldConfig = Config::factory();
        foreach($this->reports as $reportName => $formats) {
            foreach($formats as $format => $fileName) {
                display("Reporting $reportName in $format\n");
                $this->addSnitch(array('step' => 'Report : '.$format, 'project' => $config->project));
                
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
                         $e->getMessage(),
                         "\nTrying next report\n";
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
                                               
        $query = <<<GREMLIN
g.V().where( __.sideEffect{x = []; }.in('ANALYZED').sideEffect{ x.add(it.get().value('analyzer')); }.barrier().sideEffect{ y = x.groupBy().findAll{ i,j -> j.size() > 1;};} )
.filter{ y.size() > 0; }
.map{ y; };
GREMLIN;

        $res = $this->gremlin->query($query);
        if (!empty($res)) {
            file_put_contents($config->projects_root.'/projects/'.$project.'/log/doublons.log', var_export($res, true));
        }

        $this->logTime('Final');
        $this->removeSnitch();
        display("End\n");
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
