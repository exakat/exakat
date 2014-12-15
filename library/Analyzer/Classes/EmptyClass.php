<?php

namespace Analyzer\Classes;

use Analyzer;

class EmptyClass extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Exceptions\\DefinedExceptions');
    }
    
    public function analyze() {
        $this->atomIs("Class")
             ->analyzerIsNot('Analyzer\\Exceptions\\DefinedExceptions')
             ->outIs('EXTENDS')
             ->back('first')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
        
        $this->atomIs("Class")
             ->hasNoOut('EXTENDS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
