<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Data\Collector;
use Exakat\Exceptions\LoadError;
use Exakat\Exceptions\NoSuchFile;
use Exakat\Tasks\CleanDb;
use Exakat\Tasks\Load;
use Exakat\Tasks\Tasks;

class SplitGraphson {
    const CSV_SEPARATOR = ',';

    private $file_saved = 0;
    private $unlink = array();

    private static $count = -1; // id must start at 0 in batch-import

    private $tokenCounts   = array('Project' => 1);
    private $functioncalls = array();

    private $config = null;
    
    private $calls     = array();
    private $json      = array();
    private $project   = null;
    private $projectId = null;
    private $id        = 1;

    private $gsneo4j        = null;
    private $path           = null;
    private $pathDefinition = null;
    
    private $dictCode = null;
    
    private $datastore = null;
   
    public function __construct($gremlin, $config) {
        self::$count = -1;
        
        $this->config = $config;
        
        $this->gsneo4j        = $gremlin;
        $this->path           = "{$this->config->projects_root}/projects/.exakat/gsneo4j.graphson";
        $this->pathDefinition = "{$this->config->projects_root}/projects/.exakat/gsneo4j.definition.graphson";
        
        $this->dictCode  = new Collector();
        $this->datastore = new Datastore($this->config);
        
        $this->cleanCsv();
    }

    private function cleandDb() {
        display("Cleaning DB in gsneo4j\n");
        $clean = new CleanDb($this->gsneo4j, $this->config, Tasks::IS_SUBTASK);
        $clean->run();
    }

    public function finalize() {
        display("Init finalize\n");
        $begin = microtime(true);
        $query = <<<GREMLIN

g.V().hasLabel('File').addE('PROJECT').from(g.V($this->projectId));

GREMLIN;
        $res = $this->gsneo4j->query($query);
        
        $sqlite3 = new \Sqlite3("{$this->config->projects_root}/projects/.exakat/calls.sqlite");

        $outE = array();
        $res = $sqlite3->query($this->gsneo4j->getDefinitionSQL());
       
        $fp = fopen("{$this->path}.def", 'w+');
        $total = 0;
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            ++$total;
            fputcsv($fp, $row);
        }
        fclose($fp);
        
        if (empty($total)) {
            display('no definitions');
        } else {
            $query = <<<GREMLIN
getIt = { id ->
  def p = g.V(id);
  p.next();
}

new File('$this->path.def').eachLine {
    (fromVertex, toVertex) = it.split(',').collect(getIt)
    fromVertex.addEdge('DEFINITION', toVertex)
}

GREMLIN;
            $res = $this->gsneo4j->query($query);
            display('loaded definitions');
        }
        $end = microtime(true);

        self::saveTokenCounts();

        display('loaded nodes (duration : '.number_format( ($end - $begin) * 1000, 2).' ms)');

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }

    private function cleanCsv() {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
        if (file_exists($this->path.'.project')) {
            unlink($this->path.'.project');
            unlink($this->path.'.def');
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
        $fileName = 'unknown';
        
        $json = array();
        foreach($atoms as $atom) {
            if ($atom->atom === 'File') {
                $fileName = $atom->code;
            }
            
            if ($atom->atom === 'Project') {
                if ($this->projectId === null) {
                    $jsonText = json_encode($atom->toGraphsonLine($this->id)).PHP_EOL;
                    assert(!json_last_error(), 'Error encoding '.$atom->atom.' : '.json_last_error_msg());
                    
                    $fp = fopen($this->path, 'a');
                    fwrite($fp, $jsonText);
                    fclose($fp);
                    
                    $res = $this->gsneo4j->query('graph.io(IoCore.graphson()).readGraph("'.$this->path.'"); g.V().hasLabel("Project");');
                    $this->projectId = $res[0]['id'];
                    $this->project = $atom;
                }
            } else {
                $json[$atom->id] = $atom->toGraphsonLine($this->id);
            
                if ($atom->atom === 'Functioncall' && 
                    !empty($atom->fullnspath)) {
                    if (isset($this->functioncalls[$atom->fullnspath])) {
                        ++$this->functioncalls[$atom->fullnspath];
                    } else {
                        $this->functioncalls[$atom->fullnspath] = 1;
                    }
                }
            }
        }
        
        unset($links['PROJECT']);

        foreach($links as $type => $a) {
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

                        if (isset($this->tokenCounts[$type])) {
                            ++$this->tokenCounts[$type];
                        } else {
                            $this->tokenCounts[$type] = 1;
                        }
                    }
                }
            }
        }
        
        $fp = fopen($this->path, 'w+');

        foreach($json as $j) {
            if ($j->label === 'Project') {
                continue;
            }
            
            $V = $j->properties['code'][0]->value;
            $j->properties['code'][0]->value = $this->dictCode->get($V);
            
            $v = mb_strtolower($V);
            $j->properties['lccode'][0]->value = $this->dictCode->get($v);

            if (isset($j->properties['propertyname']) ) {
                $j->properties['propertyname'][0]->value = $this->dictCode->get($j->properties['propertyname'][0]->value);
            }

            if (isset($j->properties['globalvar']) ) {
                $j->properties['globalvar'][0]->value = $this->dictCode->get($j->properties['globalvar'][0]->value);
            }

            $X = $this->json_encode($j);
            assert(!json_last_error(), $fileName.' : error encoding normal '.$j->label.' : '.json_last_error_msg()."\n".print_r($j, true));
            fwrite($fp, $X.PHP_EOL);
            
            if (isset($this->tokenCounts[$j->label])) {
                ++$this->tokenCounts[$j->label];
            } else {
                $this->tokenCounts[$j->label] = 1;
            }
        }
        fclose($fp);
        $this->gsneo4j->query("graph.io(IoCore.graphson()).readGraph(\"$this->path\");");
        
        $this->datastore->addRow('dictionary', $this->dictCode->getRecent());

        unlink($this->path);
    }

    public function saveDefinitions($exakatDir, $calls) {
        //unused
    }

    public function json_encode($object) {
        // in case the function name is full of non-encodable characters.
        if (isset($object->properties['fullnspath']) && !mb_check_encoding($object->properties['fullnspath'][0]->value, 'UTF-8')) {
            $object->properties['fullnspath'][0]->value = utf8_encode($object->properties['fullnspath'][0]->value);
        }
        if (isset($object->properties['propertyname']) && !mb_check_encoding($object->properties['propertyname'][0]->value, 'UTF-8')) {
            $object->properties['propertyname'][0]->value = utf8_encode($object->properties['propertyname'][0]->value);
        }
        if (isset($object->properties['fullcode']) && !mb_check_encoding($object->properties['fullcode'][0]->value, 'UTF-8')) {
            $object->properties['fullcode'][0]->value = utf8_encode($object->properties['fullcode'][0]->value);
        }
        if (isset($object->properties['code']) && !mb_check_encoding($object->properties['code'][0]->value, 'UTF-8')) {
            $object->properties['code'][0]->value = utf8_encode($object->properties['code'][0]->value);
        }
        if (isset($object->properties['noDelimiter']) && !mb_check_encoding($object->properties['noDelimiter'][0]->value, 'UTF-8')) {
            $object->properties['noDelimiter'][0]->value = utf8_encode($object->properties['noDelimiter'][0]->value);
        }
        if (isset($object->properties['globalvar']) && !mb_check_encoding($object->properties['globalvar'][0]->value, 'UTF-8')) {
            $object->properties['globalvar'][0]->value = utf8_encode($object->properties['globalvar'][0]->value);
        }
        return json_encode($object);
    }
}

?>
