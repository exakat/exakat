<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnce extends Analyzer\Analyzer {
    
    function dependsOn() {
        return array('Analyzer\\Variables\\Blind');
    }
    
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->hasNoIn('PROPERTY') // avoid static properties
             ->analyzerIsNot("Analyzer\\Variables\\Blind")
             ->noCode(array('$GLOBALS', '$argv'))
             ->eachCounted('fullcode', 1)
             ;
    }
}

?>