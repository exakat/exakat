<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticCpm extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("MethodDefinition");
    }
    
    public function analyze() {
        $this->atomIs("Class")
             ->outIs("BLOCK")
             ->outIs("CODE")
             ->atomInside('Function')
             ->hasOut('STATIC')
             ->outIs('NAME');
        $this->prepareQuery();

        $this->atomIs("Class")
             ->outIs("BLOCK")
             ->outIs("CODE")
             ->atomInside('Ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE');
        $this->prepareQuery();
        
    }
}

?>