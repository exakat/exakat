<?php

namespace Analyzer\Structures;

use Analyzer;

class PropertyVariableConfusion extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    public function analyze() {
        $this->atomIs("Ppp")
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'name')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Variable')
             ->samePropertyAs('code', 'name')
             ->hasNoIn('DEFINE')
             ->analyzerIsNot('Analyzer\\Variables\\Arguments')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
