<?php

namespace Analyzer\Variables;

use Analyzer;

class StaticVariables extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\Variablenames');
    }
    
    public function analyze() {
        $this->atomIs("Variable")
             ->analyzerIs('Analyzer\\Variables\\Variablenames')
             ->inIs('DEFINE')
             ->atomIs('Ppp')
             ->outIs('STATIC')
             ->back('first');
    }
}

?>
