<?php

namespace Report\Content;

class Definitions extends \Report\Content {
    private $analyzers = array();
    protected $name = 'Definitions';
    
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
    
}

?>
