<?php

namespace Report\Format;

class Markdown { 
    private $output = '';
    protected static $analyzer = null;
    
    public function render($output, $data) {
        $output->push(" Text for ".get_class($this)."\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function toFile($filename) {
        return file_put_contents($filename, $this->output);
    }
    
    public function getRenderer($class) {
        $class = "\\Report\\Format\\Markdown\\$class";
        return new $class();
    }

    public function getExtension() {
        return 'md';
    }

    public function setAnalyzer($name) {
        \Report\Format\Markdown::$analyzer = $name;
    }
}

?>