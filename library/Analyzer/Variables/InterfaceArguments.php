<?php

namespace Analyzer\Variables;

use Analyzer;

class InterfaceArguments extends Analyzer\Analyzer {
    function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    function analyze() {
        $this->analyzerIs("Analyzer\\Variables\\Arguments")
             ->_as('x')
             ->in('ARGUMENT')
             ->in('ARGUMENTS')
             ->atomIs('Function')
             ->in('CODE')
             ->in('CODE')
             ->atomIs('Interface')
             ->back('x');
    }
    
}

?>