<?php

namespace Analyzer\Functions;

use Analyzer;

class UnsetOnArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Variables\\Arguments");
    }
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('unset')
             ->back('first')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->analyzerIs("Analyzer\\Variables\\Arguments")
             ->back('first');
        $this->prepareQuery();
    }
}

?>
