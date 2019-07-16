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
    private const CSV_SEPARATOR = ',';
    private const LOAD_CHUNK      = 20000;
    private const LOAD_CHUNK_LINK = 8000;

    private static $count = -1; // id must start at 0 in batch-import

    private $tokenCounts   = array('Project' => 1);
    private $functioncalls = array();

    private $config = null;
    
    private $project   = null;
    private $id0       = null;
    private $id        = 1;

    private $graphdb        = null;
    private $path           = null;
    private $pathLink       = null;
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
        $this->pathLink       = "{$this->config->tmp_dir}/graphdb.link.graphson";
        $this->pathDef        = "{$this->config->tmp_dir}/graphdb.def";
        
        $this->dictCode  = new Collector();
        $this->datastore = new Datastore($this->config);
        
        $this->cleanCsv();
    }
    
    public function __destruct() {
        $this->cleanCsv();
    }

    public function finalize(array $relicat) {
        if ($this->total !== 0) {
            $this->saveNodes();
        }

        display("Init finalize\n");
        $begin = microtime(true);
        $query = 'g.V().hasLabel("Project").id();';
        $res = $this->graphdb->query($query);
        $project_id = $res->toInt();

        $query = 'g.V().hasLabel("File").addE("PROJECT").from(__.V(' . $project_id . '));';
        $res = $this->graphdb->query($query);

        $query = 'g.V().hasLabel("Virtualglobal").addE("GLOBAL").from(__.V(' . $project_id . '));';
        $res = $this->graphdb->query($query);

        $f = fopen('php://memory', 'r+');
        $total = 0;
        $chunk = 0;

        foreach($relicat as $row) {
            fputcsv($f, $row);
            ++$total;
            ++$chunk;
        }
        if ($chunk > self::LOAD_CHUNK_LINK) {
            $f = $this->saveLinks($f);
            $chunk = 0;
        }

        $res = $this->sqlite3->query('SELECT origin, destination FROM globals');
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $row = array_map(array($this->graphdb, 'fixId'), $row);
            fputcsv($f, $row);
            ++$total;
            ++$chunk;
        }
        unset($res);
        if ($chunk > self::LOAD_CHUNK_LINK) {
            $f = $this->saveLinks($f);
            $chunk = 0;
        }

        $definitionSQL = <<<SQL
SELECT DISTINCT CASE WHEN definitions.id IS NULL THEN definitions2.id ELSE definitions.id END AS definition, GROUP_CONCAT(DISTINCT calls.id) AS call, count(calls.id) AS id
FROM calls
LEFT JOIN definitions 
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.fullnspath
LEFT JOIN definitions definitions2
    ON definitions2.type       = calls.type       AND
       definitions2.fullnspath = calls.globalpath 
WHERE (definitions.id IS NOT NULL OR definitions2.id IS NOT NULL)
GROUP BY definition
SQL;
        $res = $this->sqlite3->query($definitionSQL);
        // Fast dump, with a write to memory first
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            // Skip reflexive definitions, which never exist.
            if ($row[0] === $row[1]) { continue; }
            $total += $row[2];
            $chunk += $row[2];
            unset($row[2]);
            $row[0] = $this->graphdb->fixId($row[0]);
            $r = explode(',', $row[1]);
            $row[1] = array_map(array($this->graphdb, 'fixId'), $r);
            $row[1] = implode('-', $r);
            fputcsv($f, $row);
            
            if ($chunk > self::LOAD_CHUNK_LINK) {
                $f = $this->saveLinks($f);
                $chunk = 0;
            }
        }

        if (empty($total)) {
            display('no definitions');
        } else {
            $this->saveLinks($f);
            $chunk = 0;
            display("loaded $total definitions");
        }
        $end = microtime(true);

        self::saveTokenCounts();

        display('loaded nodes (duration : ' . number_format( ($end - $begin) * 1000, 2) . ' ms)');

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }
    
    private function saveLinks($f) {
        rewind($f);
        $fp = fopen($this->pathDef, 'w+');
        $length = fwrite($fp, stream_get_contents($f));
        fclose($fp);
        fclose($f);
        
        if ($length > 0) {
            $query = <<<GREMLIN
getIt = { id ->
  def p = g.V(id);
  p.next();
}

new File('$this->pathDef').eachLine {
    (fromVertex, target) = it.split(',')
    fromVertex = g.V(fromVertex).next();

    toVertices = target.split('-').collect(getIt);
    
    toVertices.each{
        g.V(fromVertex).addE('DEFINITION').to(V(it)).iterate()
    }

}

GREMLIN;
            $this->graphdb->query($query);
        }

        return fopen('php://memory', 'r+');
    }

    private function cleanCsv() {
        return;
        if (file_exists($this->path)) {
            unlink($this->path);
        }

        if (file_exists($this->pathLink)) {
            unlink($this->pathLink);
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

            file_put_contents($this->path, $jsonText, \FILE_APPEND);

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

        foreach($links as &$link) {
            if (isset($this->tokenCounts[$link[0]])) {
                ++$this->tokenCounts[$link[0]];
            } else {
                $this->tokenCounts[$link[0]] = 1;
            }
            
            $link[1] = $this->graphdb->fixId($link[1]);
            $link[2] = $this->graphdb->fixId($link[2]);
            $link = implode('-', $link);
        }

        $total = 0; // local total
        $append = array();
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
            $append[] = $X;

            if (isset($this->tokenCounts[$j->label])) {
                ++$this->tokenCounts[$j->label];
            } else {
                $this->tokenCounts[$j->label] = 1;
            }
            ++$this->total;

            ++$total;
        }
        file_put_contents($this->path, implode(PHP_EOL, $append) . PHP_EOL, \FILE_APPEND);
        file_put_contents($this->pathLink, implode(PHP_EOL, $links) . PHP_EOL, \FILE_APPEND);

        if ($this->total > self::LOAD_CHUNK) {
            $this->saveNodes();
        }

        $this->datastore->addRow('dictionary', $this->dictCode->getRecent());
    }
    
    private function saveNodes() {
        $this->graphdb->query("graph.io(IoCore.graphson()).readGraph(\"$this->path\");");
        unlink($this->path);

        $query = <<<GREMLIN
new File('$this->pathLink').eachLine {
    (theLabel, fromVertex, toVertex) = it.split('-');

    g.V(fromVertex).addE(theLabel).to(V(toVertex)).iterate();
}

GREMLIN;
        $this->graphdb->query($query);
        unlink($this->pathLink);

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
