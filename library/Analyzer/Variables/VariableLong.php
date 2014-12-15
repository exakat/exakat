<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableLong extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->codeLength(" > 20 ");
    }
}

?>
