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
    private static $fp_nodes_attr = null;

    private $config = null;
    
    private $isLink = false;
    
    public function __construct() {
        $this->config = \Config::factory();

        if (file_exists($this->config->projects_root.'/nodes.cypher.csv') && static::$file_saved == 0) {
            unlink($this->config->projects_root.'/nodes.cypher.csv');
            unlink($this->config->projects_root.'/nodes.cypher.attr.csv');
        } 
        if (file_exists($this->config->projects_root.'/rels.cypher.next.csv') && static::$file_saved == 0) {
            unlink($this->config->projects_root.'/rels.cypher.next.csv');
            unlink($this->config->projects_root.'/rels.cypher.element.csv');
            unlink($this->config->projects_root.'/rels.cypher.file.csv');
            unlink($this->config->projects_root.'/rels.cypher.indexed.csv');
        }
    }

    public function finalize() {
        if (!file_exists($this->config->projects_root.'/nodes.cypher.csv')) {
            return false;
        }
        
        foreach(static::$fp_rels as $fp) {
            fclose($fp);
        }
        fclose(static::$fp_nodes);
        fclose(static::$fp_nodes_attr);

        $client = new Client();

        // Load Nodes
        $queryTemplate = 'CREATE INDEX ON :Token(eid)';
        $query = new Query($client, $queryTemplate, array());
        $result = $query->getResultSet();

        display('Created index');

        $queryTemplate = <<<CYPHER
USING PERIODIC COMMIT
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
            die("Couldn't load nodes in the database\n");
        }

        display('Loaded nodes');

        $queryTemplate = <<<CYPHER
USING PERIODIC COMMIT
LOAD CSV WITH HEADERS FROM "file:{$this->config->projects_root}/nodes.cypher.attr.csv" AS csvLine
MATCH (token:Token { eid: toInt(csvLine.id)})
FOREACH(ignoreMe IN CASE WHEN csvLine.index <> "" THEN [1] ELSE [] END | SET token.index = (csvLine.index = "true"))
FOREACH(ignoreMe IN CASE WHEN csvLine.atom <> "" THEN [1] ELSE [] END | SET token.atom = csvLine.atom)
FOREACH(ignoreMe IN CASE WHEN csvLine.hidden <> "" THEN [1] ELSE [] END | SET token.hidden = (csvLine.hidden = "true"))
FOREACH(ignoreMe IN CASE WHEN csvLine.in_quote <> "" THEN [1] ELSE [] END | SET token.in_quote = (csvLine.in_quote = "true"))
FOREACH(ignoreMe IN CASE WHEN csvLine.association <> "" THEN [1] ELSE [] END | SET token.association = csvLine.association)
FOREACH(ignoreMe IN CASE WHEN csvLine.tag <> "" THEN [1] ELSE [] END | SET token.tag = csvLine.tag)
FOREACH(ignoreMe IN CASE WHEN csvLine.filename <> "" THEN [1] ELSE [] END | SET token.filename = csvLine.filename)
FOREACH(ignoreMe IN CASE WHEN csvLine.bracket <> "" THEN [1] ELSE [] END | SET token.bracket = (csvLine.bracket = "true"))
FOREACH(ignoreMe IN CASE WHEN csvLine.block <> "" THEN [1] ELSE [] END | SET token.block = (csvLine.block = "true"))
FOREACH(ignoreMe IN CASE WHEN csvLine.rank <> "" THEN [1] ELSE [] END | SET token.rank = toInt(csvLine.rank))
FOREACH(ignoreMe IN CASE WHEN csvLine.noDelimiter <> "" THEN [1] ELSE [] END | SET token.noDelimiter = csvLine.noDelimiter)
FOREACH(ignoreMe IN CASE WHEN csvLine.delimiter <> "" THEN [1] ELSE [] END | SET token.delimiter = csvLine.delimiter)
FOREACH(ignoreMe IN CASE WHEN csvLine.root <> "" THEN [1] ELSE [] END | SET token.root = (csvLine.root = "true"))
FOREACH(ignoreMe IN CASE WHEN csvLine.fullcode <> "" THEN [1] ELSE [] END | SET token.fullcode = csvLine.fullcode)
FOREACH(ignoreMe IN CASE WHEN csvLine.in_for <> "" THEN [1] ELSE [] END | SET token.in_for = (csvLine.in_for = "true"))
// Desactivate modifiedBy 
//FOREACH(ignoreMe IN CASE WHEN csvLine.modifiedBy <> "" THEN [1] ELSE [] END | SET token.modifiedBy = csvLine.modifiedBy)

CYPHER;
        try {
            $query = new Query($client, $queryTemplate, array());
            $result = $query->getResultSet();
        } catch (\Exception $e) {
            $this->cleanCsv(); 
            die("Couldn't load nodes attributes in the database\n");
        }

        display('Loaded nodes attributes');
        
        // Load relations
        $relations = array('file'    => 'FILE',
                           'element' => 'ELEMENT',
                           'next'    => 'NEXT',
                           'indexed' => 'INDEXED');
        foreach($relations as $name => $relation) {
            $queryTemplate = <<<CYPHER
USING PERIODIC COMMIT
LOAD CSV WITH HEADERS FROM "file:{$this->config->projects_root}/rels.cypher.{$name}.csv" AS csvLine
MATCH (token:Token { eid: toInt(csvLine.start)}),(token2:Token { eid: toInt(csvLine.end)})
CREATE (token)-[:$relation]->(token2)

CYPHER;
            try {
                $query = new Query($client, $queryTemplate, array());
                $result = $query->getResultSet();
            } catch (\Exception $e) {
                $this->cleanCsv(); 
                die("Couldn't load relations for $name in the database\n");
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
        unlink($this->config->projects_root.'/nodes.cypher.attr.csv');
        unlink($this->config->projects_root.'/rels.cypher.next.csv');
        unlink($this->config->projects_root.'/rels.cypher.element.csv');
        unlink($this->config->projects_root.'/rels.cypher.file.csv');
        unlink($this->config->projects_root.'/rels.cypher.indexed.csv');
    }
    
    public function save_chunk() {
        if (static::$fp_nodes === null) {
            static::$fp_nodes = fopen($this->config->projects_root.'/nodes.cypher.csv', 'a');
            static::$fp_nodes_attr = fopen($this->config->projects_root.'/nodes.cypher.attr.csv', 'a');
        }
        $fp = static::$fp_nodes;
        $fpa = static::$fp_nodes_attr;
        // adding in_quote here, as it may not appear on the first token.
        $les_cols = array('id', 'token', 'code', 'line');
        $les_attr = array('id', 'index', 'fullcode', 'atom', 'root', 'hidden', 
                          'in_quote', 'delimiter', 'noDelimiter', 'rank', 
                          'block', 'bracket', 'filename', 'tag', 'association', 'in_for' );
        //'modifiedBy',
        if (static::$file_saved == 0) {
            fputcsv($fp, $les_cols, self::CSV_SEPARATOR);
            fputcsv($fpa, $les_attr, self::CSV_SEPARATOR);
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
            $row['code'] = $this->escapeString($row['code']);
            fputcsv($fp, $row, self::CSV_SEPARATOR);

            $rowa = array();
            $count = 0;
            foreach($les_attr as $col) {
                if (isset($node[$col])) {
                    $rowa[$col] = $node[$col];
                    $count++;
                } else {
                    $rowa[$col] = '';
                }
                if ($diff = array_diff(array_keys($rowa), $les_attr, array('id'))) {
                    display('Some columns were not processed for attributes : '.join(', ', $diff).".\n");
                }
            }
            if ($count === 0) {
                continue; 
            }
            $rowa['id'] = $id;
            $rowa['fullcode'] = $this->escapeString($rowa['fullcode']);
            $rowa['delimiter'] = $this->escapeString($rowa['delimiter']);
            $rowa['noDelimiter'] = $this->escapeString($rowa['noDelimiter']);
            fputcsv($fpa, $rowa, self::CSV_SEPARATOR);
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
            static::$nodes[$this->id] = $this->node;
        } else {
            static::$nodes[$this->id] = $this->node;
        }
        
        $this->isLink = false;
        
        return $this;
    }

    public function relateTo($destination, $label) {
        static::$links[$label][] = array('origin' => $this->id, 
                                         'destination' => $destination->id, 
                                         'label' => $label
                                 );
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
