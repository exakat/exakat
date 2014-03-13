<?php

namespace Report\Format;

class Markdown { 
    private $output = '';
    
    public function render($output, $data) {
        $output->push(" Text for ".get_class($this)."\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function toFile($filename) {
        file_put_contents($filename, $this->output);
        
        return true;
    }
    
    public function getRenderer($class) {
        $class = "\\Report\\Format\\Markdown\\$class";
        print $class."\n";
        return new $class();
    }

    public function getExtension() {
        return 'md';
    }
}

?>