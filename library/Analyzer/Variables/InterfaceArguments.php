<?php

namespace Analyzer\Variables;

use Analyzer;

class InterfaceArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    public function analyze() {
// When there is function in an interface, we have no sequence.
        $this->atomIs('Variable')
             ->analyzerIs("Analyzer\\Variables\\Arguments")
             ->_as('x')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->inIs('CODE')
             ->inIs('BLOCK')
             ->atomIs('Interface')
             ->back('x');
        $this->prepareQuery();

// When there are several functions in one interface, we have a sequence.
        $this->atomIs('Variable')
             ->analyzerIs("Analyzer\\Variables\\Arguments")
             ->_as('x')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->inIs('ELEMENT')
             ->inIs('CODE')
             ->inIs('BLOCK')
             ->atomIs('Interface')
             ->back('x');
        $this->prepareQuery();
    }
    
}

?>