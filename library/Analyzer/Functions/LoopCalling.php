<?php

namespace Analyzer\Functions;

use Analyzer;

class LoopCalling extends Analyzer\Analyzer {
    public function analyze() {
        // loop of 2
        $this->atomIs("Function")
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->notSamePropertyAs('code', 'name')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('first')
             ->outIs('NAME')
             ;
        $this->prepareQuery();

        // loop of 3
        $this->atomIs("Function")
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->notSamePropertyAs('code', 'name')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->notSamePropertyAs('code', 'name')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('first')
             ->outIs('NAME')
             ;
        $this->prepareQuery();
    }
}

?>
