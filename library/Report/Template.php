<?php

namespace Report;

class Template {
    protected $analyzer = null;
    protected $data     = null;
    protected $title    = null;
    
    public function setAnalyzer($name) {
        $this->analyzer = $name;
    }

    public function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }

    public function setTitle($title) {
        $this->title = $title; 
    }

}

?>