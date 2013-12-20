<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableOneLetter extends Analyzer\Analyzer {
    function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    function analyze() {
        $this->atomIs("Variable")
             ->codeLength(" == 2 ");
//        $this->printQuery();
    }
}

?>