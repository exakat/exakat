<?php

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
        
        $res = shell_exec(<<<SHELL
mv nodes.csv ./batch-import/sampleme/
mv rels.csv ./batch-import/sampleme/

cd ./batch-import
java -server -Dfile.encoding=UTF-8 -Xmx4G -jar target/batch-import-jar-with-dependencies.jar ../neo4j/data/graph.db sampleme/nodes.csv sampleme/rels.csv 2>/dev/null
cd ..
sh scripts/restart.sh
SHELL
);

		$context_options = array (
			'http' => array (
				'method' => 'GET',
				'ignore_errors' => true,
				'header'=>
					"Content-type: application/json\r\n"
					. "Accept: application/json\r\n"
					. "User-Agent: Exakat v 1.0\r\n"
			)
		);

		$context = stream_context_create($context_options);
		$response = file_get_contents('http://localhost:7474/db/data/', false, $context);
		
		if (strpos($response, 'NOT_FOUND') !== false) {
		    sleep(1);
    		$response = file_get_contents('http://localhost:7474/db/data/', false, $context);
		}
        
        preg_match("/Importing (\d+) Nodes/is", $res, $nodes);
        preg_match("/Importing (\d+) Relationships/is", $res, $relations);
        
        $fnodes = -1;
        $fp = fopen('batch-import/sampleme/nodes.csv', 'r');
        while(fgetcsv($fp, 100000, "\t", '"')) { $fnodes++; }
        fclose($fp);
        
        $frels = -1;
        $fp = fopen('batch-import/sampleme/rels.csv', 'r');
        while(fgetcsv($fp, 1000, "\t", '"')) { $frels++; }
        fclose($fp);
        
        if ($fnodes != $nodes[1]) {
            print "Warning : didn't import enough nodes : {$fnodes} expected, {$nodes[1]} actually imported\n";
        }
        if ($frels != $relations[1]) {
            print "Warning : didn't import enough relations : {$frels} expected, {$relations[1]} actually imported\n";
        }

        return true;
    }
    
    public function save_chunk() {
        if (static::$fp_nodes === null) {
            static::$fp_nodes = fopen('nodes.csv', 'a');
        }
        $fp = static::$fp_nodes;
        // adding in_quote here, as it may not appear on the first token.
        $les_cols = array('token', 'code', 'index', 'fullcode', 'line', 'atom', 'root', 'hidden', 'compile', 'in_quote', 'in_for', 'modifiedBy', 'delimiter', 'noDelimiter', 'rank', 'dowhile', 'block', 'filename', 'tag', 'association' );
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
                    print_r($diff);
                    print "Some columns were not processed.\n";
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
