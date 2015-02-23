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
	Everyman\Neo4j\Gremlin\Query,
    Everyman\Neo4j\Index\NodeIndex;

class Analyze implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $project = $config->project;
        $begin = microtime(true);

        if ($config->program !== null) {
            $analyzer = $config->program;
            if (\Analyzer\Analyzer::getClass($analyzer)) {
                $analyzers_class = array($analyzer);
            } else {
                $r = \Analyzer\Analyzer::getSuggestionClass($analyzer);
                if (count($r) > 0) {
                    print "did you mean : ".implode(', ', str_replace('_', '/', $r))."\n";
                }
                die("No such class as '$analyzer'. Aborting\n");
            }
        } elseif ($config->thema !== null) {
            $thema = $config->thema;

            if (!$analyzers_class = \Analyzer\Analyzer::getThemeAnalyzers($thema)) {
                die("No such thema as '$thema'. Aborting\n");
            }
        } else {
            print "Usage :php exakat analyze -P <One/rule> -T <\"Thema\"> -p <project>\n";
            die();
        }

        $client = new Client();
        $log = new \Log('analyze', $config->projects_root.'/projects/'.$config->project);

        $analyzers = new NodeIndex($client, 'analyzers');

        $log->log("Analyzing project $project");
        $log->log("Runnable analyzers\t".count($analyzers_class)."");

        if ($config->noDependencies) {
            $dependencies2 = $analyzers_class;
        } else {
            $dependencies = array();
            $dependencies2 = array();
            foreach($analyzers_class as $a) {
                $d = \Analyzer\Analyzer::getInstance($a, $client);
                $configName = str_replace('/', '_', $a);
                if (null !== ($analyzerConfig = $config->$configName)) {
                    $d->setConfig($analyzerConfig);
                }
                $d = $d->dependsOn();
                if (empty($d)) {
                    $dependencies2[] = $a;
                } else {
                    $diff = array_diff($d, $dependencies2);
                    if (empty($diff)) {
                        $dependencies2[] = $a;
                    } else {
                        $dependencies[$a] = $diff;
                    }
                }
            }

            $c = count($dependencies) + 1;
            while(!empty($dependencies) && $c > count($dependencies)) {
                $c = count($dependencies);
                foreach($dependencies as $a => $d) {
                    $diff = array_diff($d, $dependencies2);
        
                    foreach($diff as $k => $v) {
                        if (!isset($dependencies[$v])) {
                            $x = \Analyzer\Analyzer::getInstance($v, $client);
                            if ($x === null) {
                                print "No such dependency as '$v'. Ignoring\n";
                                continue; 
                            }
                            $dep = $x->dependsOn();
                            if (count($dep) == 0) {
                                $dependencies2[] = $v;
                                $c++;
                            } else {
                                $dependencies[$v] = $dep;
                                $c += count($dep) + 1;
                            }
                        } elseif (count($dependencies[$v]) == 0) {
                            $dependencies2[] = $v;
                            unset($diff[$k]);
                        }
                    }
        
                    if (empty($diff)) {
                        $dependencies2[] = $a;
                        unset($dependencies[$a]);
                    } else {
                        $dependencies[$a] = $diff;
                    }
                }
            }

            if (!empty($dependencies)) {
                print "Dependencies depending on each other : can't finalize. Aborting\n";
                print_r($dependencies);
    
                die();
            }
        }

        $total_results = 0;
        $Php = new \Phpexec($config->version);

        foreach($dependencies2 as $analyzer_class) {
            $begin = microtime(true);
            $analyzer = \Analyzer\Analyzer::getInstance($analyzer_class, $client);
            $configName = str_replace(array('/', '\\'), '_', str_replace('Analyzer\\', '', $analyzer_class));
            if (null !== ($analyzerConfig = $config->$configName)) {
                $analyzer->setConfig($analyzerConfig);
            }
    
            if ($config->noRefresh && $analyzer->isDone()) {
                print "$analyzer_class is already done\n";
                continue 1; 
            }
            $analyzer->init();

            if (!$analyzer->checkPhpVersion($config->version)) {
                $analyzer = str_replace('\\', '\\\\', $analyzer_class);
            
                $query = <<<GREMLIN
        g.idx('analyzers')[['analyzer':'$analyzer']].next().setProperty('notCompatibleWithPhpVersion', '$config->version');
GREMLIN;
                $arguments = array('type' => 'IN');
                $result = new \Everyman\Neo4j\Gremlin\Query($client, $query, $arguments);

                print "$analyzer_class is not compatible with PHP version $config->version. Ignoring\n";
            } elseif (!$analyzer->checkPhpConfiguration($Php)) {
                $analyzer = str_replace('\\', '\\\\', $analyzer_class);
            
                $query = <<<GREMLIN
        g.idx('analyzers')[['analyzer':'$analyzer']].next().setProperty('notCompatibleWithPhpConfiguration', '$version');
GREMLIN;
                $arguments = array('type' => 'IN');
                $result = new \Everyman\Neo4j\Gremlin\Query($client, $query, $arguments);

                print "$analyzer_class is not compatible with PHP configuration of this version. Ignoring\n";
            } else {
                $analyzer->run();

                $count = $analyzer->getRowCount();
                $total_results += $count;
                print "$analyzer_class fait ($count)\n";
                $end = microtime(true);
                $log->log("$analyzer_class\t".($end - $begin)."\t$count");
            }
        }

        print "Done\n";
    }
}

?>