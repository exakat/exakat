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
use Exakat\Exceptions\NoSuchFile;
use Exakat\Graph\Tinkergraph as Graph;
use Exakat\Tasks\CleanDb;
use Exakat\Tasks\Load;
use Exakat\Tasks\Tasks;
use Exakat\Tokenizer\Token;

class GSNeo4j {
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
    private $id = 1;

    private $gsneo4j = null;
    private $path = null;
    private $pathDefinition = null;

    public function __construct($gremlin, $config) {
        $this->config = $config;
        
        $this->gsneo4j = $gremlin;
        $this->path = $this->config->projects_root.'/projects/.exakat/gsneo4j.graphson';
        $this->pathDefinition = $this->config->projects_root.'/projects/.exakat/gsneo4j.definition.graphson';
        
        $this->cleanCsv();
    }

    private function cleandDb() {
        display("Cleaning DB in gsneo4j\n");
        $clean = new CleanDb(new GSNeo4j($this->config), $this->config, Tasks::IS_SUBTASK);
        $clean->run();
    }

    public function finalize() {
        $jsonText = json_encode($this->project).PHP_EOL;
        assert(!json_last_error(), 'Error encoding '.$this->project->label.' : '.json_last_error_msg());
        
        $fp = fopen($this->path, 'a');
        $json = fwrite($fp, $jsonText);
        fclose($fp);

        self::saveTokenCounts();

        $sqlite3 = new \Sqlite3($this->config->projects_root.'/projects/.exakat/calls.sqlite');

        $outE = array();
        $res = $sqlite3->query('SELECT definitions.id AS definition, GROUP_CONCAT(COALESCE(calls.id, calls2.id)) AS call
FROM definitions
LEFT JOIN calls 
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.fullnspath
LEFT JOIN calls calls2
    ON definitions.type       = calls2.type       AND
       definitions.fullnspath = calls2.globalpath AND
       calls2.fullnspath      != calls2.globalpath 
WHERE calls.id IS NOT NULL OR calls2.id IS NOT NULL
GROUP BY definitions.id
       ');
       
        while($row = $res->fetchArray(SQLITE3_NUM)) {
            $outE[$row[0]] = explode(',', $row[1]);
        }
       
        $inE = array();
        $res = $sqlite3->query('SELECT calls.id AS call, GROUP_CONCAT(COALESCE(definitions.id, definitions2.id)) AS definition
FROM calls
LEFT JOIN definitions 
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.fullnspath
LEFT JOIN definitions definitions2
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.globalpath  AND
       calls.fullnspath      != calls.globalpath 
WHERE definitions.id IS NOT NULL OR definitions2.id IS NOT NULL
GROUP BY calls.id
       ');
       
        while($row = $res->fetchArray(SQLITE3_NUM)) {
           $inE[$row[0]] = explode(',', $row[1]);
        }
       
        $linksId = array();
        $fp = fopen($this->path, 'a');
        if (!is_resource($fp)) {
            throw new NoSuchFile($this->path);
        }
        $fpDefinitions = fopen($this->pathDefinition, 'r');
        if (!is_resource($fpDefinitions)) {
            throw new NoSuchFile($this->pathDefinition);
        }

        while(!feof($fpDefinitions)) {
            $row = fgets($fpDefinitions);
            if (empty($row)) {continue; }
            $json = json_decode($row);

            if (isset($inE[$json->id])) {
                $json->inE->DEFINITION = array();
                foreach($inE[$json->id] as $d) {
                    if (isset($linksId[$json->id.'->'.$d])) {
                        $id = $linksId[$json->id.'->'.$d];
                    } else {
                        $id = $this->id++;
                        $linksId[$json->id.'->'.$d] = $id;
                    }

                    $s = new \stdClass();
                    $s->id = $id;
                    $s->outV = (int) $d;

                    $json->inE->DEFINITION[] = $s;
                }
            }

            if (isset($outE[$json->id])) {
                $json->outE->DEFINITION = array();
                foreach($outE[$json->id] as $d) {
                    if (isset($linksId[$d.'->'.$json->id])) {
                        $id = $linksId[$d.'->'.$json->id];
                    } else {
                        $id = $this->id++;
                        $linksId[$d.'->'.$json->id] = $id;
                    }

                    $s = new \stdClass();
                    $s->id = $id;
                    $s->inV = (int) $d;
                    $json->outE->DEFINITION[] = $s;
                }
            }
            
            fwrite($fp, json_encode($json).PHP_EOL);
        }
        fclose($fp);
        fclose($fpDefinitions);
        unlink($this->pathDefinition);
        unset($sqlite3);
        unlink($this->config->projects_root.'/projects/.exakat/calls.sqlite');

        $this->calls = array();
        $this->json = array();
        gc_collect_cycles();
        
        display('loading nodes');

        $begin = microtime(true);
        $res = $this->gsneo4j->query('graph.io(IoCore.graphson()).readGraph("'.$this->path.'"); g.V().count();');
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

        $booleanValues = array('alternative', 'heredoc', 'reference', 'variadic', 'absolute', 'enclosing', 'bracket', 'close_tag', 'aliased', 'boolean', 'constant');
        $integerValues = array('count', 'intval', 'args_max', 'args_min');
        
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
                        $linkId = $this->id++;

                        if (isset($json[$d['destination']]->inE)) {
                            $json[$d['destination']]->inE->$type[] = (object) array("id" => $linkId,"outV" => $d['origin']);
                        } else {
                            $json[$d['destination']]->inE = (object) array( $type => [ (object) ["id" => $linkId,"outV" => $d['origin']]]);
                        }

                        if ($d['origin'] === 1) {
                            $this->project->outE->PROJECT[] = (object) array("id" => $linkId,"inV" => $d['destination']);
                        } elseif (isset($json[$d['origin']]->outE)) {
                            $json[$d['origin']]->outE->$type[] = (object) array("id" => $linkId,"inV" => $d['destination']);
                        } else {
                            $json[$d['origin']]->outE     = (object) array( $type => [ (object) ["id" => $linkId,"inV" => $d['destination']]]);
                        }
                    }
                }
            }
        }
        
        $fp = fopen($this->path, 'a');
        $fpDefinition = fopen($this->pathDefinition, 'a');

        foreach($json as $j) {
            if (in_array($j->label, array('Functioncall', 'Function', 
                                          'Class', 'Classanonymous', 'Newcall', 'Interface', 'Trait', 
                                          'Identifier', 'Nsname', 'Constant', 
                                          'String', 
//                                          'Variable', 'Variablearray', 'Variableobject', 
                                          ))) {
                $X = $this->json_encode($j);
                assert(!json_last_error(), 'Error encoding for definition '.$j->label.' : '.json_last_error_msg()."\n".' '.print_r($j, true));
                fwrite($fpDefinition, $X.PHP_EOL);
            } elseif ($j->label === 'Project') {
                // Just continue;
            } else {
                $X = $this->json_encode($j);
                assert(!json_last_error(), 'Error encoding normal '.$j->label.' : '.json_last_error_msg()."\n".print_r($j, true));
                fwrite($fp, $X.PHP_EOL);
            }
        }

        fclose($fp);
        fclose($fpDefinition);
    }

    public function saveDefinitions($exakatDir, $calls) {
        //each time...
//        $this->calls = $calls;
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
        if (isset($object->properties['globalvar'])) {
            $object->properties['globalvar'][0]->value = utf8_encode($object->properties['globalvar'][0]->value);
        }
        return json_encode($object);
    }
}

?>
