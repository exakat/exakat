<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableNonascii extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->regex('code', '[^a-zA-Z0-9\\$_]');
    }
}

?>
