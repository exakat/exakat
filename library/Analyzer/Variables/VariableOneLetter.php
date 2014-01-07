<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableOneLetter extends Analyzer\Analyzer {
    function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->fullcodeLength(" == 2 ");
    }
}

?>