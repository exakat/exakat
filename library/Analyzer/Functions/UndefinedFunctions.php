<?php

namespace Analyzer\Functions;

use Analyzer;

class UndefinedFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\IsExtFunction');
    }
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn(array('METHOD', 'NEW'))
             ->tokenIsNot(array('T_VARIABLE','T_OPEN_BRACKET'))
             ->analyzerIsNot('Analyzer\\Functions\\IsExtFunction')
             ->hasNoFunctionDefinition();
        $this->prepareQuery();
    }
}

?>
