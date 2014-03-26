<?php

namespace Report\Content;

class Definitions {
    private $analyzers = array();
    
    public function setAnalyzers($analyzers) {
        $this->analyzers = $analyzers;
    }
    
    public function getDefinitions() {
        $return = array();
        
        foreach($this->analyzers as $analyzer) {
            $o = \Analyzer\Analyzer::getInstance($analyzer, null);
            $return[$o->getName()] = $o->getDescription();
        }
        
        return $return;
    }
    
    public function getName() {
        return 'Definitions';
    }
}

?>