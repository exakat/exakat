<?php

namespace Analyzer\Classes;

use Analyzer;

class EmptyClass extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('EXTENDS')
             ->fullnspathIsNot('\exception')
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