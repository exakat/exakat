<?php

namespace Analyzer\Variables;

use Analyzer;

class Arguments extends Analyzer\Analyzer {
    
    function analyze() {
        $this->atomIs("Variable")
             ->_as('x')
             ->in('ARGUMENT')
             ->atomIs('Arguments')
             ->in('ARGUMENTS')
             ->atomIs('Function')
             ->back('x');
    }
    
}

?>