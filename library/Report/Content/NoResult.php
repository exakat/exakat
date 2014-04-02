<?php

namespace Report\Content;

class NoResult extends \Report\Content {
    private $analyzers = array();
    
    public function addAnalyzer($analyzer) {
        $a = \Analyzer\Analyzer::getInstance($analyzer, null);
        
        $this->analyzers[] = $a->getName();
    }
    
    public function toArray() {
        return $this->analyzers;
    }
}

?>