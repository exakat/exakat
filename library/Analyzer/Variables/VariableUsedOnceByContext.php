<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnceByContext extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\VariableUsedOnce',
                     'Analyzer\\Variables\\Variablenames',
                     'Analyzer\\Variables\\InterfaceArguments',
                     );
    }
    
    public function analyze() {
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->analyzerIsNot("Analyzer\\Variables\\Blind")
             ->analyzerIsNot("Analyzer\\Variables\\InterfaceArguments")
             ->codeIsNot(VariablePhp::$variables, true)
             ->analyzerIsNot("Analyzer\\Variables\\VariableUsedOnceByContext")
             ->fetchContext()
             ->eachCounted('it.code + "/" + context.Function + "/" + context.Class + "/" + context.Namespace', 1);
        $this->prepareQuery();
    }
}

?>
