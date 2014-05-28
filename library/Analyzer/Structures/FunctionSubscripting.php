<?php

namespace Analyzer\Structures;

use Analyzer;

class FunctionSubscripting extends Analyzer\Analyzer {
    protected $phpversion = "5.4+";
    
    public function analyze() {
        $this->atomIs("Array")
             ->outIs('VARIABLE')
             ->atomIs(array('Functioncall', 'Staticmethodcall', 'Methodcall'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>