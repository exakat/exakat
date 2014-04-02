<?php

namespace Report\Format;

class Sqlite { 
    private $output = array();
    protected static $analyzer = null;
    private $summary = null;
    
    public function render($output, $data) {
        // Nothing
    }
    
    public function push($render) {
        $this->output[] = $render;
    }
    
    public function toFile($filename) {
        if (file_exists($filename)) {
            unlink($filename);
        }
        $db = new \SQLite3($filename);

        $results = $db->query('CREATE TABLE reports (id INTEGER PRIMARY KEY AUTOINCREMENT, analyzer TEXT, value TEXT, count INT)');
        foreach($this->output as $t) {
            foreach($t as $k => $v) {
                $t[$k] = $db->escapeString($v);
            }
            if (count($t) != 3) {
                print_r($t);die();
            }
            $db->query("INSERT INTO reports (analyzer, value, count) VALUES ('".join("', '", $t)."')");
        }
        
        return true;
    }
    
    public function getRenderer($class) {
        $class = "\\Report\\Format\\Sqlite\\$class";

        return new $class();
    }

    public function getExtension() {
        return 'sqlite';
    }
    
    public function setAnalyzer($name) {
        \Report\Format\Sqlite::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }
}

?>