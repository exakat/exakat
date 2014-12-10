<?php

namespace Analyzer\Php;

use Analyzer;

class ConstantScalarExpression extends Analyzer\Analyzer {
    protected $phpVersion = "5.6+";
    
    public function analyze() {
        $this->atomIs("Const")
             ->outIs('VALUE')
             ->atomIsNot(array('Integer', 'Float', 'Boolean', 'String', 'Null'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Function")
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIsNot(array('Integer', 'Float', 'Boolean', 'String', 'Null'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>