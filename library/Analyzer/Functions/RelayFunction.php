<?php

namespace Analyzer\Functions;

use Analyzer;

class RelayFunction extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->savePropertyAs('fullcode', 'args')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->is('count', 1)
             ->atomInside('Functioncall')
             ->outIs('ARGUMENTS')
             ->samePropertyAs('fullcode', 'args')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
