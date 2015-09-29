<?php

namespace Analyzer\Php;

use Analyzer;

class EmptyList extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIs('T_LIST')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
