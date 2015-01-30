<?php

namespace Report\Format;

class Sqlite extends \Report\Format { 
    private $output = array();
    protected static $analyzer = null;
    private $summary = null;

    protected $fileExtension ='sqlite';

    public function __construct() {
        parent::__construct();
        
        $this->format = 'Sqlite';
    }
    
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
        $db->query('CREATE TABLE reports (id INTEGER PRIMARY KEY AUTOINCREMENT, analyzer TEXT, value TEXT, count INT)');

        foreach($this->output as $t) {
            foreach($t as $k => $v) {
                $t[$k] = $db->escapeString($v);
            }
            if (count($t) != 3) {
                print_r($t);
                die(__METHOD__);
            }
            $db->query("INSERT INTO reports (analyzer, value, count) VALUES ('".join("', '", $t)."')");
        }
        
        return true;
    }
    
    public function setAnalyzer($name) {
        \Report\Format\Sqlite::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }

}

?>
