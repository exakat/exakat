<?php

namespace Analyzer\Classes;

use Analyzer;

class UnusedMethods extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\UsedMethods',
                     'Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        // Methods definitions
        $this->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedMethods')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->codeIsNot(array('__construct', '__destruct', '__get', '__set', '__call', '__callstatic', '__tostring',
                               '__debugInfo'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>