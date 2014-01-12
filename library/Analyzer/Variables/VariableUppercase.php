<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUppercase extends Analyzer\Analyzer {
    function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->noCode(VariablePhp::$variables, true)
             ->codeIsUppercase()
             ;
    }
}

?>