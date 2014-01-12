<?php

namespace Analyzer\Variables;

use Analyzer;

class Variablenames extends Analyzer\Analyzer {
    function analyze() {
        $this->atomIs("Variable")
//             ->hasNoIn('DEFINE')
             ->hasNoParent('Class', array('DEFINE', 'ELEMENT', 'CODE', 'BLOCK'))
             ->hasNoParent('Staticproperty', 'PROPERTY')
             ->hasNoParent('Staticproperty', array('VARIABLE', 'PROPERTY'))
             ->analyzerIsNot("Analyzer\\Variables\\Blind");
    }
}

?>