<?php

namespace Analyzer\Functions;

use Analyzer;

class funcGetArgModified extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->outIsIE('RIGHT')
             ->savePropertyAs('rank', 'rank')
             ->savePropertyAs('code', 'arg')
             ->inIsIE('RIGHT')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->fullnspath('\\func_get_arg')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Integer')
             ->samePropertyAs('intval', 'rank', true)
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->samePropertyAs('code', 'arg')
             ->analyzerIs('Variables/IsModified')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
