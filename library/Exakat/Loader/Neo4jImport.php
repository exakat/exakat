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

    private $file_saved = 0;

    private $tokenCounts   = array();

    private $indexList = array('Analysis');

    private $config = null;

    public function __construct($config) {
        $this->config = $config;
        
        if (file_exists($this->config->projects_root.'/projects/.exakat/nodes.g3.csv') && $this->file_saved == 0) {
            $this->cleanCsv();
        }
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

        foreach($this->indexList as $indice => $foo) {
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

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }

    private function cleanCsv() {
        unlink($this->config->projects_root.'/projects/.exakat/nodes.g3.csv');
        unlink($this->config->projects_root.'/projects/.exakat/rels.g3.csv');
    }

    private function saveTokenCounts() {
        $datastore = new Datastore($this->config);

        $datastore->addRow('tokenCounts', $this->tokenCounts);
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
                               'alternative' => 'boolean',
                               'reference'   => 'boolean',
                               'heredoc'     => 'boolean',
                               'delimiter'   => '',
                               'noDelimiter' => '',
                               'variadic'    => 'boolean',
                               'count'       => 'int',
                               'fullnspath'  => '',
                               'absolute'    => 'boolean',
                               'alias'       => '',
                               'origin'      => '',
                               'encoding'    => '',
                               'block'       => '',
                               'intval'      => 'long',
                               'strval'      => '',
                               'enclosing'   => 'boolean',
                               'args_max'    => 'int',
                               'args_min'    => 'int',
                               'bracket'     => 'boolean',
                               'close_tag'   => 'boolean',
                               'aliased'     => 'boolean',
                               'boolean'     => 'boolean',
                               'propertyname'=> '',
                               'constant'    => 'boolean',
                               'root'        => 'int',
                               'globalvar'   => '',
                               'binaryString'=> '');

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
        foreach($atoms as $id => $atom) {
            if ($atom == $id0) { continue; }

            $this->indexList[$atom->atom] = 1;
            $ids[$id] = 1;

            $written = fputcsv($fp, $atom->toArray());
        }
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
                        fputcsv($fp, array($link['origin'], $link['destination'], $label));
                    }
                }
            }
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
                                fputcsv($fp, array($d, $o, 'DEFINITION'));
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
