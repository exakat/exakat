<?php

namespace Analyzer\Common;

class Bunch extends \Analyzer\Analyzer {
    
    public function setBunch($array) {
        // @todo check this
        $this->bunch = $array; 
    }
    
    public function toFullArray() {
        $return = array();

        new \Analyzer\Common\Void($this->client);
        foreach($this->bunch as $analyzer) {
            $object = \Analyzer\Analyzer::getInstance($analyzer, $this->client);
            
            $return = array_merge($return, $object->toFullArray());
        }
        
        return $return;
    }
}

?>
