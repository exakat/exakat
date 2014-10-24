<?php

namespace Analyzer\Structures;

use Analyzer;

class EmptyLines extends Analyzer\Analyzer {
    public function analyze() {
        // one void in the sequence
        $this->atomIs("Void")
             ->hasIn('ELEMENT')
             ->isNot('rank', '0');
        $this->prepareQuery();

        // if the void is only one, we must check if this is a condition
        $this->atomIs("Void")
             ->is('rank', '0')
             ->inIs('ELEMENT')
             ->hasNoIn('BLOCK')
             ->back('first')
             ;
        $this->prepareQuery();
    }
}

?>