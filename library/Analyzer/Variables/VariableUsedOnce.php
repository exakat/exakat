<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnce extends Analyzer\Analyzer {
    
    function dependsOn() {
        return array('Analyzer\\Variables\\Blind',
                     'Analyzer\\Variables\\InterfaceArguments',
                     );
    }
    
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->analyzerIsNot("Analyzer\\Variables\\Blind")
             ->analyzerIsNot("Analyzer\\Variables\\InterfaceArguments")
             ->hasNoParent('Staticproperty')
             ->noCode(VariablePhp::$variables)
             ->eachCounted('fullcode', 1)
             ;
    }
}

?>