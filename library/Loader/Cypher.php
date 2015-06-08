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


namespace Loader;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex,
    Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Cypher\Query;

class Cypher {
    const CSV_SEPARATOR = ',';
    
    private $node = null;
    private static $nodes = array();
    private static $file_saved = 0;

    private static $links = array();
    private static $lastLink = array();

    private static $cols = array();
    private static $count = -1; // id must start at 0 in batch-import
    private $id = 0;
    
    private static $fp_rels = null;
    private static $fp_nodes = null;
    private static $fp_nodes_attr = array();
    private static $indexedId = array();

    private $config = null;
    
    private $isLink = false;
    
    private $les_attr = array('index',  'root', 'hidden', 
                              'in_quote', 'delimiter', 'noDelimiter', 'rank', 
                              'block', 'bracket', 'filename', 'tag', 'association', 'in_for', 'atom', 'fullcode' );
    
    public function __construct() {
        $this->config = \Config::factory();

        if (file_exists($this->config->projects_root.'/nodes.cypher.csv') && static::$file_saved == 0) {
            $this->cleanCsv();
        }
        
        $node = array('inited' => true);
        $this->node = &$node;
    }

    public function finalize() {
        if (!file_exists($this->config->projects_root.'/nodes.cypher.csv')) {
            return false;
        }
        
        foreach(static::$fp_rels as $fp) {
            fclose($fp);
        }
        fclose(static::$fp_nodes);
        foreach(static::$fp_nodes_attr as $fpa) {
            fclose($fpa);
        }

        $client = new Client();

        // Load Nodes
        $queryTemplate = 'CREATE INDEX ON :Token(eid)';
        $query = new Query($client, $queryTemplate, array());
        $result = $query->getResultSet();

        display('Created index');

        $queryTemplate = <<<CYPHER
USING PERIODIC COMMIT 200
LOAD CSV WITH HEADERS FROM "file:{$this->config->projects_root}/nodes.cypher.csv" AS csvLine
CREATE (token:Token { 
eid: toInt(csvLine.id),
token: csvLine.token,
code: csvLine.code,
line: toInt(csvLine.line)})

CYPHER;
        try {
            $query = new Query($client, $queryTemplate, array());
            $result = $query->getResultSet();
        } catch (\Exception $e) {
            $this->cleanCsv(); 
            die("Couldn't load nodes in the database\n".$e->getMessage());
        }

        display('Loaded nodes');

        foreach($this->les_attr as $attribute) {
            display( "Loading $attribute");
            
            if ($attribute == 'rank') {
                $toAttribute = "toInt(csvLine.rank)";
            } elseif (in_array($attribute, array('index', 'hidden', 'in_quote', 'bracket', 'block', 'in_for', 'root'))) {
                $toAttribute = "(csvLine.$attribute = \"true\")";
            } else {
                $toAttribute = "csvLine.$attribute";
            }
            $queryTemplate = <<<CYPHER
USING PERIODIC COMMIT 200
LOAD CSV WITH HEADERS FROM "file:{$this->config->projects_root}/nodes.cypher.$attribute.csv" AS csvLine
MATCH (token:Token { eid: toInt(csvLine.id)})
SET token.$attribute = $toAttribute

CYPHER;
            try {
                $query = new Query($client, $queryTemplate, array());
                $result = $query->getResultSet();
            } catch (\Exception $e) {

                die("Couldn't load nodes attributes '$attribute' in the database\n Exception : ".$e->getMessage()."\n");
            }
        }

        display('Loaded nodes attributes');
        
        // Load relations
        $relations = array('file'    => 'FILE',
                           'element' => 'ELEMENT',
                           'next'    => 'NEXT',
                           'indexed' => 'INDEXED');
        foreach($relations as $name => $relation) {
            $queryTemplate = <<<CYPHER
USING PERIODIC COMMIT 200
LOAD CSV WITH HEADERS FROM "file:{$this->config->projects_root}/rels.cypher.{$name}.csv" AS csvLine
MATCH (token:Token { eid: toInt(csvLine.start)}),(token2:Token { eid: toInt(csvLine.end)})
CREATE (token)-[:$relation]->(token2)

CYPHER;
            try {
                $query = new Query($client, $queryTemplate, array());
                $result = $query->getResultSet();
            } catch (\Exception $e) {
                $this->cleanCsv(); 
                die("Couldn't load relations for $name in the database\n".$e->getMessage());
            }

            display('Loaded link '.$name);
        }
/*
        $queryTemplate = <<<CYPHER
DROP INDEX ON :Token(eid)
CYPHER;
        try {
            $query = new Query($client, $queryTemplate, array());
            $result = $query->getResultSet();
        } catch (\Exception $e) {
            $this->cleanCsv(); 
            die("Couldn't remove eid\n");
        } 

        display('dropped index on eid ');

        $queryTemplate = <<<CYPHER
MATCH (Token {  })
REMOVE Token.eid
CYPHER;
        try {
            $query = new Query($client, $queryTemplate, array());
            $result = $query->getResultSet();
        } catch (\Exception $e) {
            $this->cleanCsv(); 
            die("Couldn't remove eid\n");
        } 

        display('cleaned eid');
*/
        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }
    
    private function cleanCsv() {
        unlink($this->config->projects_root.'/nodes.cypher.csv');
        foreach($this->les_attr as $attribute) {
            unlink($this->config->projects_root.'/nodes.cypher.'.$attribute.'.csv');
        }
        unlink($this->config->projects_root.'/rels.cypher.next.csv');
        unlink($this->config->projects_root.'/rels.cypher.element.csv');
        unlink($this->config->projects_root.'/rels.cypher.file.csv');
        unlink($this->config->projects_root.'/rels.cypher.indexed.csv');
    }
    
    public function save_chunk() {
        if (static::$fp_nodes === null) {
            static::$fp_nodes = fopen($this->config->projects_root.'/nodes.cypher.csv', 'a');
            foreach($this->les_attr as $attribute) {
                static::$fp_nodes_attr[$attribute] = fopen($this->config->projects_root.'/nodes.cypher.'.$attribute.'.csv', 'a');
            }
        }

        $fp = static::$fp_nodes;
//        $fpa = static::$fp_nodes_attr;
        // adding in_quote here, as it may not appear on the first token.
        $les_cols = array('id', 'token', 'code', 'line');
        //'modifiedBy',
        if (static::$file_saved == 0) {
            fputcsv($fp, $les_cols, self::CSV_SEPARATOR);
            foreach(static::$fp_nodes_attr as $attribute => $fpa) {
                fputcsv($fpa, array('id', $attribute), self::CSV_SEPARATOR);
            }
        }

        foreach(static::$nodes as $id => $node) {
            $row = array();
            foreach($les_cols as $col) {
                if (isset($node[$col])) {
                    $row[$col] = $node[$col];
                } else {
                    if ($col == 'line') {
                        $row['line'] = 0;
                    } else {
                        $row[$col] = '';
                    }
                }
                if ($diff = array_diff(array_keys($row), $les_cols, array('id'))) {
                    display('Some columns were not processed : '.join(', ', $diff).".\n");
                }
            }
            $row['id'] = $id;
            if (isset($node['index']) && ($row['code'] != 'Index for S_ARRAY') && !isset(static::$indexedId[$row['id']])) {
                continue;
            }
            $row['code'] = $this->escapeString($row['code']);
            fputcsv($fp, $row, self::CSV_SEPARATOR);

        // processing the attributes
            foreach($this->les_attr as $col) {
                $rowa = array('id' => $id);
                if (isset($node[$col])) {
                    $rowa[$col] = $node[$col];
                    if (in_array($col, array('fullcode', 'delimiter', 'noDelimiter'))) {
                        $rowa[$col] = $this->escapeString($rowa[$col]);
                    }
                    fputcsv(static::$fp_nodes_attr[$col], $rowa, self::CSV_SEPARATOR);
                } 
            }
        }
        static::$nodes = array();
        
        if (static::$fp_rels === null) {
            static::$fp_rels = array('NEXT'    => fopen($this->config->projects_root.'/rels.cypher.next.csv', 'a'),
                                     'FILE'    => fopen($this->config->projects_root.'/rels.cypher.file.csv', 'a'),
                                     'INDEXED' => fopen($this->config->projects_root.'/rels.cypher.indexed.csv', 'a'),
                                     'ELEMENT' => fopen($this->config->projects_root.'/rels.cypher.element.csv', 'a'),
                                     );
        }
        if (static::$file_saved == 0) {
            foreach(static::$fp_rels as $key => $fp) {
                fputcsv($fp, array('start', 'end', 'type'), self::CSV_SEPARATOR);
            }
        }
        foreach(static::$links as $label => $links) {
            if (!isset(static::$fp_rels[$label])) {
                die(print_r(array_keys(static::$fp_rels), true)."\nNO $label\n");
            }
            $fp = static::$fp_rels[$label];
            foreach($links as $id => $link) {
                if (isset($link['namespace'])) {
                    $link['namespace'] = $this->escapeString($link['namespace']);
                }
            
                fputcsv($fp, $link, self::CSV_SEPARATOR);
            }
        }
        static::$links = array();
        static::$file_saved++;
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
            static::$count++;
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
}
?>
