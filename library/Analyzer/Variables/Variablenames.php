<?php

namespace Analyzer\Variables;

use Analyzer;

class Variablenames extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Variable")
             ->hasNoParent('Class', array('DEFINE', 'ELEMENT', 'CODE', 'BLOCK'))
             ->hasNoParent('Staticproperty', 'PROPERTY')
             ->hasNoParent('Staticproperty', array('VARIABLE', 'PROPERTY'))
             ->analyzerIsNot("Analyzer\\Variables\\Blind");
    }
}

?>