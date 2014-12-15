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
             ->raw('filter{ it.out.loop(1){true}{it.object.atom == "Variable"}.has("code",value).filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any()}.any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
