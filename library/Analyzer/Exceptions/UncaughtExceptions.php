<?php

namespace Analyzer\Exceptions;

use Analyzer;

class UncaughtExceptions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Exceptions/CaughtExceptions',
                     'Exceptions/DefinedExceptions');
    }
    
    public function analyze() {
        $this->atomIs('Throw')
             ->outIs('THROW')
             ->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->classDefinition()
             ->analyzerIs('Exceptions/DefinedExceptions')
             ->analyzerIsNot('Exceptions/CaughtExceptions');
        $this->prepareQuery();
    }
}

?>
