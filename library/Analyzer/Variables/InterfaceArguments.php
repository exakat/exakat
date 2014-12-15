<?php

namespace Analyzer\Variables;

use Analyzer;

class InterfaceArguments extends Analyzer\Analyzer {
    public function analyze() {
    // When there are several functions in one interface, we have a sequence.
        $this->atomIs('Variable')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs('Interface')
             ->back('first');
        $this->prepareQuery();

        // with default value
        $this->atomIs('Variable')
             ->inIs('LEFT')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs('Interface')
             ->back('first');
        $this->prepareQuery();

        // with typehint
        $this->atomIs('Variable')
             ->inIs('VARIABLE')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs('Interface')
             ->back('first');
        $this->prepareQuery();

        // with typehint and default value
        $this->atomIs('Variable')
             ->inIs('LEFT')
             ->inIs('VARIABLE')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs('Interface')
             ->back('first');
        $this->prepareQuery();

    }
    
}

?>
