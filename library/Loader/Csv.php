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
    
    
    private $isLink = false;
    
    function __construct() {
        if (file_exists('nodes.csv') && static::$file_saved == 0) {
            unlink('nodes.csv');
        } 
        if (file_exists('rels.csv') && static::$file_saved == 0) {
            unlink('rels.csv');
        }
    }

    static function finalize() {
        if (!file_exists('nodes.csv')) {
            return false;
        }
        shell_exec(<<<SHELL
mv nodes.csv ./batch-import/sampleme/
mv rels.csv ./batch-import/sampleme/

cd ./batch-import
#sh sampleme/import.sh
java -server -Xmx1G -Dfile.encoding=UTF-8 -jar target/batch-import-jar-with-dependencies.jar ../neo4j/data/graph.db sampleme/nodes.csv sampleme/rels.csv
cd ..
sh scripts/restart.sh
SHELL
);
        return true;
    }
    
    function save_chunk() {
        $fp = fopen('nodes.csv', 'a');
        // adding in_quote here, as it may not appear on the first token.
        $les_cols = array('token', 'code', 'index', 'fullcode', 'line', 'atom', 'root', 'hidden', 'compile', 'in_quote', 'in_for', 'modifiedBy', 'delimiter', 'noDelimiter' );
        if (static::$file_saved == 0) {
            fputcsv($fp, array_merge($les_cols), "\t");
        }
        foreach(static::$nodes as $id => $node) {
            $row = array();
            foreach($les_cols as $col) {
                if (isset($node[$col])) {
                    $row[$col] = $node[$col];
                } else {
                    $row[$col] = '';
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
        fclose($fp);
        static::$nodes = array();
        
        $fp = fopen('rels.csv', 'a');
        if (static::$file_saved == 0) {
            if (isset($row['namespace'])) {
                $row['namespace'] = str_replace("\\", "\\\\", $row['namespace']);
                $row['namespace'] = str_replace("\"", "\\\"", $row['namespace']);
            }

            fputcsv($fp, array('start', 'end', 'type', 'classname', 'function', 'namespace', 'file'), "\t");
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

    function getProperty($name) {
        if ($this->isLink) {
            return static::$links[count(static::$links) - 1][$name];
        } else {
            return $this->node[$name];
        }
    }
    
    function save() {
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

    function relateTo($destination, $label) {
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
    
    function escapeString($string) {
        $x = str_replace("\\", "\\\\", $string);
        return str_replace("\"", "\\\"", $x);
    }
}
?>