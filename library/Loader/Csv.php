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

class Csv {
    private $node = null;
    private static $nodes = array();
    private static $file_saved = 0;
    private static $links = array();
    private static $cols = array();
    private static $count = -1; // id must start at 0 in batch-import
    private $id = 0;
    
    private static $fp_rels = null;
    private static $fp_nodes = null;
    
    private $isLink = false;
    
    public function __construct() {
        if (file_exists('nodes.csv') && static::$file_saved == 0) {
            unlink('nodes.csv');
        } 
        if (file_exists('rels.csv') && static::$file_saved == 0) {
            unlink('rels.csv');
        }
    }

    static public function finalize() {
        if (!file_exists('nodes.csv')) {
            return false;
        }
        
        fclose(static::$fp_rels);
        fclose(static::$fp_nodes);
        
        $config = \Config::factory();
        
        $res = shell_exec(<<<SHELL

cd ./batch-import
java -server -Dfile.encoding=UTF-8 -Xmx4G -jar target/batch-import-jar-with-dependencies.jar {$config->neo4j_folder}/data/graph.db ../nodes.csv ../rels.csv 2>/dev/null
cd {$config->neo4j_folder}
./bin/neo4j restart
sleep 1

SHELL
);

		$context_options = array (
			'http' => array (
				'method' => 'GET',
				'ignore_errors' => true,
				'header'=>
					<<<HEADER
Content-type: application/json
Accept: application/json
User-Agent: Exakat v 1.0

HEADER
			)
		);

		$context = stream_context_create($context_options);
		$config = \Config::factory();
		$response = file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/db/data/', false, $context);
		
		if (strpos($response, 'NOT_FOUND') !== false) {
    		$response = file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/db/data/', false, $context);
		}
        
        preg_match("/Importing (\d+) Nodes/is", $res, $nodes);
        preg_match("/Importing (\d+) Relationships/is", $res, $relations);
        
        $fnodes = -1;
        $fp = fopen('nodes.csv', 'r');
        while(fgetcsv($fp, 100000, "\t", '"')) { $fnodes++; }
        fclose($fp);
        
        $frels = -1;
        $fp = fopen('rels.csv', 'r');
        while(fgetcsv($fp, 1000, "\t", '"')) { $frels++; }
        fclose($fp);
        
        if ($fnodes != $nodes[1]) {
            display( "Warning : didn't import enough nodes : {$fnodes} expected, {$nodes[1]} actually imported\n");
        } else {
            display( "{$nodes[1]} nodes imported\n");
            display( number_format(filesize('nodes.csv') / 1024, 0)." ko imported\n");
            unlink('nodes.csv');
        }
        if ($frels != $relations[1]) {
            display( "Warning : didn't import enough relations : {$frels} expected, {$relations[1]} actually imported\n");
        } else {
            display( "{$relations[1]} relations imported\n");
            unlink('rels.csv');
        }

        return true;
    }
    
    public function save_chunk() {
        if (static::$fp_nodes === null) {
            static::$fp_nodes = fopen('nodes.csv', 'a');
        }
        $fp = static::$fp_nodes;
        // adding in_quote here, as it may not appear on the first token.
        $les_cols = array('token', 'code', 'index', 'fullcode', 'line', 'atom', 'root', 'hidden', 'compile', 
                          'in_quote', 'in_for', 'modifiedBy', 'delimiter', 'noDelimiter', 'rank', 
                          'block', 'bracket', 'filename', 'tag', 'association');
        if (static::$file_saved == 0) {
            $les_cols2 = $les_cols;
            $les_cols2[4] .= ':int';
            fputcsv($fp, $les_cols2, "\t");
            unset($les_cols2);
        }
        foreach(static::$nodes as $id => $node) {
            $row = array();
            foreach($les_cols as $col) {
                if (isset($node[$col])) {
                    $row[$col] = $node[$col];
                } else {
                    if ($col == 'line') {
                        $row[$col] = 0;
                    } else {
                        $row[$col] = '';
                    }
                }
                if ($diff = array_diff(array_keys($row), $les_cols, array('id'))) {
                    display("Some columns were not processed : ".implode(", ", $diff).".\n");
                }
            }
            $row['code'] = $this->escapeString($row['code']);
            $row['fullcode'] = $this->escapeString($row['fullcode']);
            $row['delimiter'] = $this->escapeString($row['delimiter']);
            $row['noDelimiter'] = $this->escapeString($row['noDelimiter']);
            fputcsv($fp, $row, "\t");
        }
        static::$nodes = array();
        
        if (static::$fp_rels === null) {
            static::$fp_rels = fopen('rels.csv', 'a');
        }
        $fp = static::$fp_rels;
        if (static::$file_saved == 0) {
            fputcsv($fp, array('start', 'end', 'type', 'classname', 'function', 'namespace', 'file'), "\t");
        }
        foreach(static::$links as $id => $link) {
            if (isset($link['namespace'])) {
                $link['namespace'] = $this->escapeString($link['namespace']);
            }

            fputcsv($fp, $link, "\t");
        }
        static::$links = array();
        static::$file_saved++;
    }
    
    public function makeNode() {
        return new static();
    }
    
    public function setProperty($name, $value) {
        if ($this->isLink) {
            static::$links[count(static::$links) - 1][$name] = $value;
        } else {
            if (!isset(static::$cols[$name])) { 
                static::$cols[$name] = true; 
            }

            $this->node[$name] = $value;
        }
        
        return $this;
    }

    public function getProperty($name) {
        if ($this->isLink) {
            return static::$links[count(static::$links) - 1][$name];
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
        static::$links[] = array('origin' => $this->id, 
                                 'destination' => $destination->id, 
                                 'label' => $label,
                                 'classname' =>  '',
                                 'function' => '',
                                 'namespace' => '',
                                 'file' => '',
                                 );
        $this->isLink = true;

        return $this;
    }
    
    public function escapeString($string) {
        $x = str_replace("\\", "\\\\", $string);
        return str_replace("\"", "\\\"", $x);
    }
}
?>
