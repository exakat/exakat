<?php

namespace Report\Format;

class Text extends \Report\Format { 
    private $output = '';
    protected static $analyzer = null;
    private $summary = null;

    protected $fileExtension ='txt';

    public function __construct() {
        parent::__construct();
        
        $this->format = 'Text';
    }
    
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
        \Report\Format\Text::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }

}

?>
