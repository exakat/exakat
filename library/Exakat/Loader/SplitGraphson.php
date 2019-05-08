<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class SplitGraphson extends Loader {
    const CSV_SEPARATOR = ',';

    private static $count = -1; // id must start at 0 in batch-import

    private $tokenCounts   = array('Project' => 1);
    private $functioncalls = array();

    private $config = null;
    
    private $project   = null;
    private $id0       = null;
    private $id        = 1;

    private $graphdb        = null;
    private $path           = null;
    private $pathDef        = null;
    private $total          = 0;
    
    private $dictCode = null;
    
    private $datastore = null;
    private $sqlite3   = null;
   
    public function __construct($gremlin, $config, \Sqlite3 $sqlite3) {
        self::$count = -1;
        
        $this->config = $config;
        
        $this->graphdb        = $gremlin;
        $this->sqlite3        = $sqlite3;
        $this->path           = "{$this->config->tmp_dir}/graphdb.graphson";
        $this->pathDef        = "{$this->config->tmp_dir}/graphdb.def";
        
        $this->dictCode  = new Collector();
        $this->datastore = new Datastore($this->config);
        
        $this->cleanCsv();
    }
    
    public function __destruct() {
        $this->cleanCsv();
    }

    public function finalize() {
        if ($this->total !== 0) {
            $this->saveNodes();
        }

        display("Init finalize\n");
        $begin = microtime(true);
        $query = 'g.V().hasLabel("Project").id();';
        $res = $this->graphdb->query($query);

        $query = 'g.V().hasLabel("File").addE("PROJECT").from(__.V(' . $res->toInt() . '));';
        $res = $this->graphdb->query($query);
        
        $res = $this->sqlite3->query($this->graphdb->getDefinitionSQL());

        $total = 0;
        // Fast dump, with a write to memory first
        $f = fopen('php://memory', 'r+');
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            ++$total;
            // Skip reflexive definitions, which never exist.
            if ($row[0] === $row[1]) { continue; }
            fputcsv($f, $row);
        }
        rewind($f);
        $fp = fopen($this->pathDef, 'w+');
        fputs($fp, stream_get_contents($f));
        fclose($fp);
        fclose($f);
        
        if (empty($total)) {
            display('no definitions');
        } else {
            $query = <<<GREMLIN
getIt = { id ->
  def p = g.V(id);
  p.next();
}

new File('$this->pathDef').eachLine {
    (fromVertex, toVertex) = it.split(',').collect(getIt)
    fromVertex.addEdge('DEFINITION', toVertex)
}

GREMLIN;
            $res = $this->graphdb->query($query);
            display("loaded $total definitions");
        }
        $end = microtime(true);

        self::saveTokenCounts();

        display('loaded nodes (duration : ' . number_format( ($end - $begin) * 1000, 2) . ' ms)');

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }

    private function cleanCsv() {
        if (file_exists($this->path)) {
            unlink($this->path);
        }

        if (file_exists($this->pathDef)) {
            unlink($this->pathDef);
        }
    }

    private function saveTokenCounts() {
        $datastore = new Datastore($this->config);

        $datastore->addRow('tokenCounts', $this->tokenCounts);
    }

    public function saveFiles($exakatDir, $atoms, $links, $id0) {
        $fileName = 'unknown';
        
        if (empty($this->id0)) {
            $jsonText = json_encode($id0->toGraphsonLine($id0)) . PHP_EOL;
            assert(!json_last_error(), 'Error encoding ' . $id0->atom . ' : ' . json_last_error_msg());
            
            $fp = fopen($this->path, 'a');
            fwrite($fp, $jsonText);
            fclose($fp);
            
            ++$this->total;
            $this->id0 = $id0;
        }

        $json = array();
        foreach($atoms as $atom) {
            if ($atom->atom === 'File') {
                $fileName = $atom->code;
            }
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

        foreach($links as $type => $a) {
            foreach($a as $b) {
                foreach($b as $c) {
                    foreach($c as $d) {
                        $linkId = $this->id++;

                        if (isset($json[$d['destination']]->inE)) {
                            $json[$d['destination']]->inE->$type[] = (object) array('id' => $linkId,'outV' => $d['origin']);
                        } else {
                            $json[$d['destination']]->inE = (object) array( $type => array( (object) array('id' => $linkId,'outV' => $d['origin'])));
                        }

                        if ($d['origin'] === 1) {
                            $this->project->outE->PROJECT[] = (object) array('id' => $linkId,'inV' => $d['destination']);
                        } elseif (isset($json[$d['origin']]->outE)) {
                            $json[$d['origin']]->outE->$type[] = (object) array('id' => $linkId,'inV' => $d['destination']);
                        } else {
                            $json[$d['origin']]->outE     = (object) array( $type => array( (object) array('id' => $linkId,'inV' => $d['destination'])));
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
        
        $total = 0; // local total
        $fp = fopen($this->path, 'a');
        foreach($json as $j) {
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
            assert(!json_last_error(), $fileName . ' : error encoding normal ' . $j->label . ' : ' . json_last_error_msg() . "\n" . print_r($j, true));
            fwrite($fp, $X . PHP_EOL);
            
            if (isset($this->tokenCounts[$j->label])) {
                ++$this->tokenCounts[$j->label];
            } else {
                $this->tokenCounts[$j->label] = 1;
            }
            ++$this->total;
            ++$total;
        }
        fclose($fp);

        $total_log = $this->total;
        if ($this->total > 5000) {
            $this->saveNodes();
        }

        $this->datastore->addRow('dictionary', $this->dictCode->getRecent());
    }
    
    private function saveNodes() {
        $this->graphdb->query("graph.io(IoCore.graphson()).readGraph(\"$this->path\");");
        unlink($this->path);
        $this->total = 0;
    }

    private function json_encode($object) {
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
