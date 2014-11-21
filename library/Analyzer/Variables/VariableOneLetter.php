<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableOneLetter extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->fullcodeLength(" == 2 ");
        $this->prepareQuery();
        
        $this->atomIs("Variable")
             ->tokenIs('T_DOLLAR')
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->fullcodeLength(" == 1 ");
        $this->prepareQuery();
        
    }
}

?>