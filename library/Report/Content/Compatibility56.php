<?php

namespace Report\Content;

class Compatibility56 extends \Report\Content {
    protected $array = array();
    
    public function collect() {
        $list = \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP56');
        
        foreach($list as $l) {
            $analyzer = \Analyzer\Analyzer::getInstance($l, $this->neo4j);
            $this->array[ $analyzer->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }
        
        return true;
    }
}

?>