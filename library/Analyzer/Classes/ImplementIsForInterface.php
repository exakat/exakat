<?php

namespace Analyzer\Classes;

use Analyzer;

class ImplementIsForInterface extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Interfaces\\IsExtInterface');
    }
    
    public function analyze() {
        // class a with implements
        $this->atomIs("Class")
             ->outIs('IMPLEMENTS')
             ->hasClassDefinition()
             ->analyzerIsNot('Analyzer\\Interfaces\\IsExtInterface')
             ->back('first');
        $this->prepareQuery();    
    }
}

?>
