<?php

namespace Analyzer\Variables;

use Analyzer;

class Variablenames extends Analyzer\Analyzer {
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->hasNoParent('Staticproperty')
             ->analyzerIsNot("Analyzer\\Variables\\Blind")
             ;
    }
}

?>