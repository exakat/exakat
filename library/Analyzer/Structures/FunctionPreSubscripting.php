<?php

namespace Analyzer\Structures;

use Analyzer;

class FunctionPreSubscripting extends Analyzer\Analyzer {
    protected $phpVersion = "5.4+";
    
    public function analyze() {
        // $x = f(); 
        // $x['e'] 
        // instead of f()['e']
        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIs(array('Functioncall', 'Staticmethodcall', 'Methodcall'))
             ->back('first')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varray')
             ->back('first')
             ->nextSibling() // one must check more than the next sibling
             ->atomIs('Assignation')
             ->_as('second')
             ->outIs('RIGHT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'varray')
             ->back('first');
        $this->prepareQuery();
    }
}

?>