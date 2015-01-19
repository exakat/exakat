<?php

namespace Analyzer\Functions;

use Analyzer;

class ShouldBeTypehinted extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    public function analyze() {
        // spotting objects with property
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting objects with methodcall
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();


        // spotting array with array[index]
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting array with arrayappend[]
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Arrayappend')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting array in a functioncall
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_OPEN_BRACKET')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting array with callable
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_VARIABLE')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
