<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnce extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\Blind',
                     'Analyzer\\Variables\\InterfaceArguments',
                     'Analyzer\\Variables\\Variablenames'
                     );
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->analyzerIsNot("Analyzer\\Variables\\Blind")
             ->analyzerIsNot("Analyzer\\Variables\\InterfaceArguments")
             ->codeIsNot(VariablePhp::$variables, true)
             ->eachCounted('code', 1)
             ;
    }
}

?>