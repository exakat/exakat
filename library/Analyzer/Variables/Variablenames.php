<?php

namespace Analyzer\Variables;

use Analyzer;

class Variablenames extends Analyzer\Analyzer {
    
    function dependsOn() {
        return array('Analyzer\Classes\PropertyDefinition');
    }

    function analyze() {
        $this->atomIs("Variable")
             ->analyzerIsNot('Analyzer\\\\Classes\\\\PropertyDefinition');
    }
}

?>