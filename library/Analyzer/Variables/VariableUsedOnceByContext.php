<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnceByContext extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\VariableUsedOnce');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIsNot("Analyzer\\Variables\\VariableUsedOnceByContext")
             ->eachCounted('code + "/" + it.method + "/" + it.class + "/" + it.namespace', 1);
        $this->prepareQuery();
    }
}

?>