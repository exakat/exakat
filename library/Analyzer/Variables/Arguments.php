<?php

namespace Analyzer\Variables;

use Analyzer;

class Arguments extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs('Variable')
             ->inIs('ARGUMENT')
             ->atomIs('Arguments')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('first');
        $this->prepareQuery();

        // with default value 
        $this->atomIs('Variable')
             ->inIs('LEFT')
             ->inIs('ARGUMENT')
             ->atomIs('Arguments')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('first');
        $this->prepareQuery();

        // with typehint
        $this->atomIs('Variable')
             ->inIs('VARIABLE')
             ->inIs('ARGUMENT')
             ->atomIs('Arguments')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('first');
        $this->prepareQuery();

        // with typehint and default value
        $this->atomIs('Variable')
             ->inIs('LEFT')
             ->inIs('VARIABLE')
             ->inIs('ARGUMENT')
             ->atomIs('Arguments')
             ->inIs('ARGUMENTS')
             ->atomIs('Function')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
