<?php

namespace Analyzer\Variables;

use Analyzer;

class InterfaceArguments extends Analyzer\Analyzer {
    function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    function analyze() {
// When there is function in an interface, we have no sequence.
        $this->atomIs('Variable')
             ->analyzerIs("Analyzer\\Variables\\Arguments")
             ->_as('x')
             ->in('ARGUMENT')
             ->in('ARGUMENTS')
             ->atomIs('Function')
             ->in('CODE')
             ->in('CODE')
             ->atomIs('Interface')
             ->back('x');
        $this->prepareQuery();

// When there are several functions in one interface, we have a sequence.
        $this->atomIs('Variable')
             ->analyzerIs("Analyzer\\Variables\\Arguments")
             ->_as('x')
             ->in('ARGUMENT')
             ->in('ARGUMENTS')
             ->atomIs('Function')
             ->in('ELEMENT')
             ->in('CODE')
             ->in('CODE')
             ->atomIs('Interface')
             ->back('x');
        $this->prepareQuery();
    }
    
}

?>