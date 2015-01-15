<?php

namespace Analyzer\Classes;

use Analyzer;

class ShouldUseThis extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("UseThis");
    }
    
    public function analyze() {
        // Non-Static Methods must use $this
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->analyzerIsNot('Analyzer\\Classes\\UseThis')
             ->hasNoOut('STATIC');
        $this->prepareQuery();

        // Static Methods must use a static call to property or variable (not constant though)
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->analyzerIsNot('Analyzer\\Classes\\UseThis')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
