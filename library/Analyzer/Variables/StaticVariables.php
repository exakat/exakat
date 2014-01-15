<?php

namespace Analyzer\Variables;

use Analyzer;

class StaticVariables extends Analyzer\Analyzer {
    
    function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->in('DEFINE')
             ->back('first');
    }
}

?>