<?php

namespace Report;

class Template {
    protected $analyzer = null;
    
    public function setAnalyzer($name) {
        $this->analyzer = $name;
    }

    function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }
}

?>