<?php

namespace Loader;

class Csv {
    private $node = null;
    private static $nodes = array();
    private static $file_saved = 0;
    private static $links = array();
    private static $cols = array();
    private static $count = 0;
    private $id = 0;
    
    function __construct() {
        if (file_exists('nodes.csv') && static::$file_saved == 0) {
            unlink('nodes.csv');
        } 
        if (file_exists('rels.csv') && static::$file_saved == 0) {
            unlink('rels.csv');
        }
    }

    static function finalize() {
        shell_exec(<<<SHELL
mv nodes.csv ./batch-import/sampleme/
mv rels.csv ./batch-import/sampleme/

cd neo4j
./bin/neo4j stop
cd ../batch-import
sh sampleme/import.sh
cd -
./bin/neo4j start
cd ..        
php bin/build_root
SHELL
);
    }
    
    function save_chunk() {
        $fp = fopen('nodes.csv', 'a');
        $les_cols = array_keys(static::$cols);
        if (static::$file_saved == 0) {
            fputcsv($fp, $les_cols, "\t");
        }
        foreach(static::$nodes as $id => $node) {
            $row = array();
            foreach($les_cols as $col) {
                if (isset($node[$col])) {
                    $row[$col] = $node[$col];
                } else {
                    $row[$col] = '';
                }
            }
            $row['code'] = str_replace("\\", "\\\\", $row['code']);
            $row['code'] = str_replace("\"", "\\\"", $row['code']);
            fputcsv($fp, $row, "\t");
        }
        fclose($fp);
        static::$nodes = array();
        
        $fp = fopen('rels.csv', 'a');
        if (static::$file_saved == 0) {
            fputcsv($fp, array('start', 'end', 'type'), "\t");
        }
        foreach(static::$links as $link) {
            fputcsv($fp, $link, "\t");
        }
        fclose($fp);
        static::$links = array();
        static::$file_saved++;
        
    }
    
    function makeNode() {
        return new static();
    }
    
    function setProperty($name, $value) {
        if (!isset(static::$cols[$name])) { 
            static::$cols[$name] = true; 
        }

        $this->node[$name] = $value;
        
        return $this;
    }
    
    function save() {
        if (empty($this->id)) {
            static::$count++;
            $this->id = static::$count;
            static::$nodes[$this->id] = $this->node;
        } else {
            static::$nodes[$this->id] = $this->node;
        }
        
        return $this;
    }

    function relateTo($destination, $label) {
        static::$links[] = array('origin' => $this->id, 
                                 'destination' => $destination->id, 
                                 'label' => $label);

        return $this;
    }
}
?>