<?php

namespace Report\Format;

class Markdown extends \Report\Format { 
    private $output = '';
    protected static $analyzer = null;
    private $summary = null;

    protected $format = "Markdown";
    protected $fileExtension ='md';
    
    public function render($output, $data) {
        $output->push(" Text for ".get_class($this)."\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function toFile($filename) {
        return file_put_contents($filename, $this->output);
    }

    public function setAnalyzer($name) {
        \Report\Format\Markdown::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }
}

?>