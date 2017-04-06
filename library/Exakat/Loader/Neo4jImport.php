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
use Exakat\Exceptions\GremlinException;
use Exakat\Graph\Cypher;
use Exakat\Graph\Gremlin3;
use Exakat\Tasks\Load;
use Exakat\Tasks\Tasks;
use Exception;

class Neo4jImport {
    const CSV_SEPARATOR = ',';

    private $node = null;
    private static $nodes = array();
    private static $file_saved = 0;
    private $unlink = array();

    private static $links = array();
    private static $lastLink = array();

    private static $cols = array();
    private static $count = -1; // id must start at 0 in batch-import
    private $id = 0;

    private static $fp_rels       = null;
    private static $fp_nodes      = null;
    private static $fp_nodes_attr = array();
    private static $indexedId     = array();
    private static $tokenCounts   = array();

    private $config = null;

    private $isLink = false;

    private $cypher = null;

    public function __construct() {
        $this->config = Config::factory();

        if (file_exists($this->config->projects_root.'/projects/.exakat/nodes.g3.csv') && static::$file_saved == 0) {
            $this->cleanCsv();
        }

        $node = array('inited' => true);
        $this->node = &$node;
    }

    public function finalize() {
        self::saveTokenCounts();

        $shell = <<<SHELL
cd {$this->config->neo4j_folder};
./bin/neo4j stop 2>&1 >/dev/null;
rm -rf data;
mkdir data;
mkdir data/log;
./bin/neo4j-import  --multiline-fields=true \
                    --into data/graph.db \
                    --nodes {$this->config->projects_root}/projects/.exakat/nodes.g3.csv \
                    --relationships {$this->config->projects_root}/projects/.exakat/rels.g3.csv;
SHELL;
        if (Tasks::$semaphore !== null) {
            $res = exec($shell);
            fclose(Tasks::$semaphore);
            exec("cd {$this->config->neo4j_folder}; ./bin/neo4j start");
            Tasks::$semaphore = @stream_socket_server("udp://0.0.0.0:".Tasks::$semaphorePort, $errno, $errstr, STREAM_SERVER_BIND);
        } else {
            $res = exec($shell);
            exec("cd {$this->config->neo4j_folder}; ./bin/neo4j start");
        }

        display('Loaded links');

        $cypher = new Cypher($this->config );

        $round = 0;
        while ($round < 3) {
            $check = $cypher->query('start n=node(*) match n return count(n)');
            ++$round;
            if ($check !== null && $check->data[0][0] > 3) {
                break 1;
            }
            sleep($round);
        }   

        if ($round > 3) {
            throw new GremlinException('Couldn\'t load any nodes. Return message "'.$res.'"'.var_export($check));
        }

        $fp = fopen($this->config->projects_root.'/projects/.exakat/index.g3.csv', 'r');
        while($indice = fgets($fp)) {
            $queryTemplate = 'CREATE INDEX ON :'.trim($indice).'(id)';
            $cypher->query($queryTemplate);
        }

        unset($cypher);

        $gremlin = new Gremlin3($this->config);
        // Finish noDelimiter for strings
        $query = <<<GREMLIN
g.V().hasLabel("String").not(has("noDelimiter"))
                        .has("code", within('""', "''"))
                        .sideEffect{ 
                            it.get().property("noDelimiter", "" ); 
                        }

GREMLIN;
        $gremlin->query($query);

        // Finish noDelimiter for strings
        $properties = array('alternative', 'reference', 'heredoc', 'variadic', 'absolute','enclosing', 'bracket', 'close_tag', 'aliased', 'boolean');
        foreach($properties as $property) {
            $query = <<<GREMLIN
g.V().has("$property").sideEffect{ 
    it.get().property("$property", it.get().property("$property").value() == 1);
}

GREMLIN;
            $gremlin->query($query);
        }

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }

    private function cleanCsv() {
        return;
        unlink($this->config->projects_root.'/projects/.exakat/nodes.g3.csv');
        unlink($this->config->projects_root.'/projects/.exakat/rels.g3.csv');
        unlink($this->config->projects_root.'/projects/.exakat/index.g3.csv');
    }

    private static function saveTokenCounts() {
        $config = Config::factory();
        $datastore = new Datastore($config);

        $datastore->addRow('tokenCounts', static::$tokenCounts);
    }

    public function makeNode() {
        return new static();
    }

    public function setProperty($name, $value) {
        if ($this->isLink) {
            static::$lastLink[$name] = $value;
        } else {
            if (!isset(static::$cols[$name])) {
                static::$cols[$name] = true;
            }

            $this->node[$name] = $value;
        }

        return $this;
    }

    public function hasProperty($name) {
        if ($this->isLink) {
            return isset(static::$lastLink[$name]);
        } else {
            return isset($this->node[$name]);
        }
    }

    public function getProperty($name) {
        if ($this->isLink) {
            return static::$lastLink[$name];
        } else {
            return $this->node[$name];
        }
    }

    public function save() {
        if (empty($this->id)) {
            ++static::$count;
            $this->id = static::$count;
            static::$nodes[$this->id] = &$this->node;
        } else {
            static::$nodes[$this->id] = &$this->node;
        }

        $this->isLink = false;

        return $this;
    }

    public function relateTo($destination, $label) {
        static::$links[$label][] = array('origin' => $this->id,
                                         'destination' => $destination->id,
                                         'label' => $label
                                 );

        if (isset($this->node['index'])) {
            static::$indexedId[$this->id] = 1;
        }

        static::$lastLink = &static::$links[$label][count(static::$links[$label]) - 1];
        $this->isLink = true;

        return $this;
    }

    public function escapeString($string) {
        $x = str_replace("\\", "\\\\", $string);
        return str_replace("\"", "\\\"", $x);
    }

    private function escapeCsv($string) {
        return str_replace(array('\\', '"'), array('\\\\', '\\"'), $string);
    }

    public function saveFiles($exakatDir, $atoms, $links, $id0) {
        static $extras = array(':ID'         => '',
                               ':LABEL'      => '',
                               'code'        => '',
                               'fullcode'    => '',
                               'line'        => 'int',
                               'token'       => '',
                               'rank'        => 'int',
                               'alternative' => 'int',
                               'reference'   => 'int',
                               'heredoc'     => 'int',
                               'delimiter'   => '',
                               'noDelimiter' => '',
                               'variadic'    => 'int',
                               'count'       => 'int',
                               'fullnspath'  => '',
                               'absolute'    => 'int',
                               'alias'       => '',
                               'origin'      => '',
                               'encoding'    => '',
                               'intval'      => 'long',
                               'strval'      => '',
                               'enclosing'   => 'int',
                               'args_max'    => 'int',
                               'args_min'    => 'int',
                               'bracket'     => 'int',
                               'close_tag'   => 'int',
                               'aliased'     => 'int',
                               'boolean'     => 'int',
                               'propertyname'=> '',
                               'constant'    => '',
                               'root'        => 'int',
                               'globalvar'   => '');

        $fileName = $exakatDir.'/nodes.g3.csv';
        if (file_exists($fileName)) {
            $fp = fopen($fileName, 'a');
        } else  {
            $fp = fopen($fileName, 'w+');
            $headers = array();
            foreach($extras as $name => $type) {
                $headers[] = empty($type) ? $name : $name.':'.$type;
            }
            fputcsv($fp, $headers);

            $projectRow = array($id0->id,
                                $id0->atom,
                                $this->escapeCsv( $id0->code ),
                                $this->escapeCsv( $id0->fullcode),
                                (isset($id0->line) ? $id0->line : 0),
                                $this->escapeCsv( isset($id0->token) ? $id0->token : ''),
                                (isset($id0->rank) ? $id0->rank : -1));
            $projectRow = array_pad($projectRow, count($headers), '');

            $written = fputcsv($fp, $projectRow);
        }

          // Saving atoms
        $ids = array();
        $starts = array();
        $ends = array();
        $indexList = array();
        foreach($atoms as $id => $atom) {
            if ($atom == $id0) { continue; }

            $indexList[$atom->atom] = 1;
            $ids[$id] = 1;

            $written = fputcsv($fp, $atom->toArray(), ',', '"', '\\');
        }
        fclose($fp);

        $fileName = $exakatDir.'/index.g3.csv';
        $fp = fopen($fileName, 'w+');
        fwrite($fp, implode("\n", array_keys($indexList)));
        fclose($fp);

        $fileName = $exakatDir.'/rels.g3.csv';
        if (file_exists($fileName)) {
            $fp = fopen($fileName, 'a');
        } else  {
            $fp = fopen($fileName, 'w+');
            fputcsv($fp, array(':START_ID', ':END_ID', ':TYPE'));
        }

        // Saving the links between atoms
        foreach($links as $label => $origins) {
            foreach($origins as $origin => $destinations) {
                foreach($destinations as $destination => $links) {
                    assert(!empty($origin),  "Unknown origin for Rel files\n");
                    assert(!empty($destination),  "Unknown destination for Rel files\n");
                    $csv = $label.'.'.$origin.'.'.$destination;
                    foreach($links as $link) {
                        $starts[$link['origin']] = 1;
                        $ends[$link['destination']] = 1;
                        fputcsv($fp, array($link['origin'], $link['destination'], $label), ',', '"', '\\');
                    }
                }
            }
        }

        $d = array_diff(array_keys($starts), array_keys($ids));
        if (!empty($d)) {
            //            print "Starts \n";
            //            print_r($d);
        }
        $d = array_diff(array_keys($ends), array_keys($ids));
        if (!empty($d)) {
            //            print "Ends \n";
            //            print_r($d);
        }
    }

    public function saveDefinitions($exakatDir, $calls) {
        $fileName = $exakatDir.'/rels.g3.csv';
        $fp = fopen($fileName, 'a');

        // Saving the function / class definitions
        foreach($calls as $type => $paths) {
            foreach($paths as $path) {
                foreach($path['calls'] as $origin => $origins) {
                    foreach($path['definitions'] as $destination => $destinations) {
                        foreach($origins as $o) {
                            foreach($destinations as $d) {
                                fputcsv($fp, array($d, $o, 'DEFINITION'), ',', '"', '\\');
                            }
                        }
                    }
                }
            }
        }
        fclose($fp);
    }

}

?>
