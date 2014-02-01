<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUppercase extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->codeIsNot(VariablePhp::$variables, true)
             ->codeIsNot('$_', true)
             ->fullcodeIsUppercase()
             ;
    }
}

?>