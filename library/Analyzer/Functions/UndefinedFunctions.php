<?php

namespace Analyzer\Functions;

use Analyzer;

class UndefinedFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\UsedFunctions');
    }
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIsNot('T_VARIABLE')
             ->analyzerIsNot('Analyzer\\Functions\\IsExtFunction')
             ->analyzerIsNot('Analyzer\\Functions\\UsedFunctions');
        $this->prepareQuery();
    }
}

?>