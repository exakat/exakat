<?php

namespace Report\Format;

class Csv { 
    private $output = array();
    protected static $analyzer = null;
    
    public function render($output, $data) {
        $output->push(array(" Text for ".get_class($this).""));
    }
    
    public function push($render) {
        $this->output[] = $render;
    }
    
    public function toFile($filename) {
        $fp = fopen($filename, 'w+');
        fputcsv($fp, array('code', 'file', 'row', 'description'));
        foreach($this->output as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
        
        return true;
    }
    
    public function getRenderer($class) {
        $class = "\\Report\\Format\\Csv\\$class";
        return new $class();
    }

    public function getExtension() {
        return 'csv';
    }

    public function setAnalyzer($name) {
        \Report\Format\Csv::$analyzer = $name;
    }
}

?>