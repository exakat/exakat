<?php

namespace Analyzer\Structures;

use Analyzer;

class ConcatNumbers extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Concatenation")
             ->outIs('CONCAT')
             ->atomIs('Integer')
             ->back('first');
        $this->prepareQuery();
    }
}

?>