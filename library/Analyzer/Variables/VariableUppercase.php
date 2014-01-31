<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUppercase extends Analyzer\Analyzer {
    function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->codeIsNot(VariablePhp::$variables, true)
             ->codeIsNot('_')
             ->codeIsUppercase()
             ;
    }
}

?>