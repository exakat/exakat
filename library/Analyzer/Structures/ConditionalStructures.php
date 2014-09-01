<?php

namespace Analyzer\Structures;

use Analyzer;

class ConditionalStructures extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        // classes, interfaces, Traits
        $this->atomIs(array("Class", 'Interface', 'Trait'))
             ->analyzerIsNot('Analyzer\\Classes\\MethodDefinition')
             ->atomAboveIs('Ifthen')
             ->back('first');
        $this->prepareQuery();

        // functions 
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIsNot('Analyzer\\Classes\\MethodDefinition')
             ->back('first')
             ->atomAboveIs('Ifthen')
             ->back('first');
        $this->prepareQuery();

       // constants
        $this->atomIs('Functioncall')
             ->fullnspath('\\define')
             ->atomAboveIs('Ifthen')
             ->back('first')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('order', 0);
        $this->prepareQuery();
 
    }
}

?>