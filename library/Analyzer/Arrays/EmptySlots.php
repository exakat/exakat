<?php

namespace Analyzer\Arrays;

use Analyzer;

class EmptySlots extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Functioncall")
             ->tokenIs('T_ARRAY')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>