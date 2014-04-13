<?php

namespace Analyzer\Functions;

use Analyzer;

class Recursive extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("MethodDefinition");
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->samePropertyAs('code', 'name', false)
             ->back('first')
             ->outIs('NAME')
             ;
    }
}

?>