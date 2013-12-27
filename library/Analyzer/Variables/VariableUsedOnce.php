<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnce extends Analyzer\Analyzer {
    
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->hasNoIn('PROPERTY') // avoid static properties
             ->noCode(array('$GLOBALS', '$argv'))
             ->eachCounted('fullcode', 1)
             ;
//             $this->printQuery();
    }
}

?>