<?php

namespace Report\Format;

class Html { 
    private $output = '';
    protected static $analyzer = null;
    
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
    
    public function getRenderer($class) {
        $class = "\\Report\\Format\\Html\\$class";
        return new $class();
    }

    public function getExtension() {
        return 'html';
    }

    public function setAnalyzer($name) {
        \Report\Format\Html::$analyzer = $name;
    }

}

?>