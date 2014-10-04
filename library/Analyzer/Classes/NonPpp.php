<?php

namespace Analyzer\Classes;

use Analyzer;

class NonPpp extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("MethodDefinition");
    }
    
    public function analyze() {
        $this->atomIs("Identifier")
             ->analyzerIs("Analyzer\\Classes\\MethodDefinition")
             ->inIs('NAME')
             ->hasNoOut(array('PUBLIC', 'PROTECTED', 'PRIVATE'));
        $this->prepareQuery();

        $this->atomIs("Ppp")
             ->hasNoOut(array('PUBLIC', 'PROTECTED', 'PRIVATE'))
             ->hasOut('DEFINE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs(array('Class', 'Trait'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>