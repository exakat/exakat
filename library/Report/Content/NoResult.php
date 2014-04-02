<?php

namespace Report\Content;

class NoResult {
    private $analyzers = array();
    
    public function __construct() { }

    public function addAnalyzer($analyzer) {
        $a = \Analyzer\Analyzer::getInstance($analyzer, null);
        
        $this->analyzers[] = $a->getName();
    }
    
    public function toArray() {
        return $this->analyzers;
    }
}

?>