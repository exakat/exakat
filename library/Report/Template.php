<?php

namespace Report;

class Template {
    protected $analyzer = null;
    
    public function setAnalyzer($name) {
        $this->analyzer = $name;
    }
}

?>