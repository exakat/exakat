<?php

namespace Analyzer\Structures;

use Analyzer;

class TernaryInConcat extends Analyzer\Analyzer {
    public function analyze() {
        // 'a'. 'b'.$c > 1 ? 'd' : 'e'; Ternary has priority
        $this->atomIs('Ternary')
             ->outIs('CONDITION')
             ->atomIs('Comparison')
             ->outIs('LEFT')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
