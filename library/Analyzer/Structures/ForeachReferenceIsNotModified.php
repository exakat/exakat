<?php

namespace Analyzer\Structures;

use Analyzer;

class ForeachReferenceIsNotModified extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified');
    }
    
    public function analyze() {
        $this->atomIs("Foreach")
             ->outIs('VALUE')
             ->is('reference', 'true')
             ->savePropertyAs('code', 'value')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->analyzerIs('Analyzer\\Variables\\IsModified')
             ->back('first');
        $this->prepareQuery();
    }
}

?>