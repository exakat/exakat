<?php

namespace Report\Format;

class Text { 
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
        $class = "\\Report\\Format\\Text\\$class";
        return new $class();
    }

    public function getExtension() {
        return 'txt';
    }

    public function setAnalyzer($name) {
        \Report\Format\Text::$analyzer = $name;
    }
}

?>