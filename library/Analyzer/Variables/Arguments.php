<?php

namespace Analyzer\Variables;

use Analyzer;

class Arguments extends Analyzer\Analyzer {
    
    function analyze() {
        $this->atomIs("Variable")
             ->_as('x')
             ->inIs('ARGUMENT')
             ->atomIs('Arguments')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->back('x')
             ->setApplyBelow(true);
    }
    
}

?>