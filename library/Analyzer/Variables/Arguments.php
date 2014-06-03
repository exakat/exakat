<?php

namespace Analyzer\Variables;

use Analyzer;

class Arguments extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Variable")
             ->_as('x')
             ->inIs('ARGUMENT')
             ->atomIs('Arguments')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('x');
    }
}

?>