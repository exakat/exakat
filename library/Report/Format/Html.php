<?php

namespace Report\Format;

class Html extends \Report\Format { 
    private $output = '';
    protected static $analyzer = null;
    private $summary = null;

    protected $format = "Html";
    protected $fileExtension ='html';
    
    public function render($output, $data) {
        $output->push(" Text for ".get_class($this)."\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function toFile($filename) {
        file_put_contents($filename, "<html><header></header><body>".$this->output."</body>");
        
        return true;
    }

    public function setAnalyzer($name) {
        \Report\Format\Html::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }

}

?>