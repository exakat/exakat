<?php

namespace Report\Content;

class Compatibility54 extends \Report\Content {
    protected $array = array();
    
    public function collect() {
        $list = \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54');
        
        foreach($list as $l) {
            $analyzer = \Analyzer\Analyzer::getInstance($l, $this->neo4j);
            $this->array[ $analyzer->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }
        
        return true;
    }
}

?>