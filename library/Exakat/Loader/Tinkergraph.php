<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Loader;

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\LoadError;
use Exakat\Graph\Tinkergraph as Graph;
use Exakat\Tasks\CleanDb;
use Exakat\Tasks\Load;
use Exakat\Tasks\Tasks;
use Exakat\Tokenizer\Token;
use Exakat\Tasks\Helpers\Atom;

class Tinkergraph {
    const CSV_SEPARATOR = ',';

    private $file_saved = 0;
    private $unlink = array();

    private static $count = -1; // id must start at 0 in batch-import

    private $tokenCounts   = array();

    private $labels = array();
    private $edges = array();
    
    private $config = null;
    
    private $calls = array();
    private $json = array();
    private $project = null;
    private $id = 0;

    private $tinkergraph = null;
    private $path = null;

    public function __construct($gremlin, $config) {
        $this->config = $config;
        
        // Force autoload
        $this->tinkergraph = new Graph($this->config);
        $this->path = $this->config->projects_root.'/projects/.exakat/tinkergraph.graphson';
        $this->pathDefinition = $this->config->projects_root.'/projects/.exakat/tinkergraph.definition.graphson';
        
        $this->cleanCsv();
    }

    private function cleandDb() {
        display("Cleaning DB in tinkergraph\n");
        $clean = new CleanDb(new Gremlin3($this->config), $this->config, Tasks::IS_SUBTASK);
        $clean->run();
    }

    public function finalize() {
        $jsonText = json_encode($this->project).PHP_EOL;
        assert(!json_last_error(), 'Error encoding '.$this->project->label.' : '.json_last_error_msg());
        
        $fp = fopen($this->path, 'a');
        $json = fwrite($fp, $jsonText);
        fclose($fp);

        self::saveTokenCounts();

        $links = array();
        foreach($this->calls as $type => $fnps) {
            foreach($fnps as $fnp => $usage) {
                if (empty($usage['definitions'])) { continue; }
                if (empty($usage['calls'])) { continue; }
                
                $calls = array_merge(...array_values($usage['calls']));
                $definitions = array_merge(...array_values($usage['definitions']));
                
                foreach($calls as $call) {
                    $links[$call] = $definitions;
                }
            }
        }

        $fp = fopen($this->path, 'a');
        $fpDefinitions = fopen($this->pathDefinition, 'r');
        while(!feof($fpDefinitions)) {
            $row = fgets($fpDefinitions);
            if (empty($row)) {continue; }
            $json = json_decode($row);
            
            if (!isset($links[$json->id])) {
                fwrite($fp, $row);
                continue; 
            }

            $json->inE->DEFINITION = array();
            foreach($links[$json->id] as $d) {
                $json->inE->DEFINITION[] = (object) ["id" => $this->id++,"outV" => $d];
            }
            
            fwrite($fp, json_encode($json).PHP_EOL);
        }
        fclose($fp);
        fclose($fpDefinitions);
        unlink($this->pathDefinition);
        
        display('loading nodes');

        $begin = microtime(true);
        $res = $this->tinkergraph->query('graph.io(IoCore.graphson()).readGraph("'.$this->path.'"); g.V().count();');
        $end = microtime(true);

        display('loaded nodes (duration : '.number_format( ($end - $begin) * 1000, 2).' ms)');

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }

    private function cleanCsv() {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
        if (file_exists($this->pathDefinition)) {
            unlink($this->pathDefinition);
        }
    }

    private function saveTokenCounts() {
        $datastore = new Datastore($this->config);

        $datastore->addRow('tokenCounts', $this->tokenCounts);
    }

    private function escapeCsv($string) {
        return str_replace(array('\\', '"'), array('\\\\', '\\"'), $string);
    }

    public function saveFiles($exakatDir, $atoms, $links, $id0) {
        $json = array();
        foreach($atoms as $atom) {
            $this->labels[$atom->atom] = 1;
            $json[$atom->id] = $atom->toGraphsonLine($this->id);
        }
        
        if ($this->project === null) {
            $this->project = $json[1];
        }
        
        foreach($links as $type => $a) {
            $this->edges[$type] = 1;
            foreach($a as $b) {
                foreach($b as $c) {
                    foreach($c as $d) {
                        if ($d['origin'] === 1) {
                            $this->project->outE->PROJECT[] = (object) array("id" => $this->id++,"inV" => $d['destination']);
                        } elseif (isset($json[$d['origin']]->outE)) {
                            $json[$d['origin']]->outE->$type[] = (object) array("id" => $this->id++,"inV" => $d['destination']);
                        } else {
                            $json[$d['origin']]->outE     = (object) array( $type => [ (object) ["id" => $this->id++,"inV" => $d['destination']]]);
                        }

                        if (isset($json[$d['destination']]->inE)) {
                            $json[$d['destination']]->inE->$type[] = (object) array("id" => $this->id++,"outV" => $d['origin']);
                        } else {
                            $json[$d['destination']]->inE = (object) array( $type => [ (object) ["id" => $this->id++,"outV" => $d['origin']]]);
                        }
                    }
                }
            }
        }
        
        $fp = fopen($this->path, 'a');
        $fpDefinition = fopen($this->pathDefinition, 'a');

        foreach($json as $j) {
            if (in_array($j->label, array('Functioncall', 'Function', 'Class', 'Classanonymous', 'Newcall', 'Variableobject', 
                                          'Identifier', 'Nsname', 'Interface', 'Trait', 'String', 'Constant', 
                                          'Variable', 'Variablearray', ))) {
                assert(!json_last_error(), 'Error encoding '.$j->label.' : '.json_last_error_msg()."\n".print_r($j, true));
                fwrite($fpDefinition, $this->json_encode($j).PHP_EOL);
            } elseif ($j->label === 'Project') {
                // Just continue;
            } else {
                assert(!json_last_error(), 'Error encoding '.$j->label.' : '.json_last_error_msg()."\n".print_r($j, true));
                fwrite($fp, $this->json_encode($j).PHP_EOL);
            }
        }

        fclose($fp);
        fclose($fpDefinition);
    }

    public function saveDefinitions($exakatDir, $calls) {
        //each time...
        $this->calls = $calls;
    }
    
    public function json_encode($object) {
        if (isset($object->properties['fullcode'])) {
            $object->properties['fullcode'][0]->value = utf8_encode($object->properties['fullcode'][0]->value);
        }
        if (isset($object->properties['code'])) {
            $object->properties['code'][0]->value = utf8_encode($object->properties['code'][0]->value);
        }
        if (isset($object->properties['noDelimiter'])) {
            $object->properties['noDelimiter'][0]->value = utf8_encode($object->properties['noDelimiter'][0]->value);
        }
        return json_encode($object);
    }
}

?>
