<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableVariables extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->tokenIs(array('T_DOLLAR', 'T_DOLLAR_OPEN_CURLY_BRACES'))
             ;
    }
}

?>
