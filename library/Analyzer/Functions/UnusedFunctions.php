<?php

namespace Analyzer\Functions;

use Analyzer;

class UnusedFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\UsedFunctions');
    }
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->analyzerIsNot('Analyzer\\Functions\\UsedFunctions');
        $this->prepareQuery();
    }
}

?>