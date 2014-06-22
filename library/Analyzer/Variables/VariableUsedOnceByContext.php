<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnceByContext extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\VariableUsedOnce');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->analyzerIsNot("Analyzer\\Variables\\Blind")
             ->analyzerIsNot("Analyzer\\Variables\\InterfaceArguments")
             ->codeIsNot(VariablePhp::$variables, true)
             ->analyzerIsNot("Analyzer\\Variables\\VariableUsedOnceByContext")
             ->eachCounted('code + "/" + it.method + "/" + it.class + "/" + it.namespace', 1);
        $this->prepareQuery();
    }
}

?>