<?php

namespace Report\Format;

class Csv extends \Report\Format { 
    private $output = array();
    private $summary = null;
    protected static $analyzer = null;
    
    protected $format = "Csv";
    protected $fileExtension ='csv';
    
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
    
    public function setAnalyzer($name) {
        \Report\Format\Csv::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }
}

?>
