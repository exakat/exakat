<?php

namespace Analyzer\Structures;

use Analyzer;

class ComparedComparison extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Comparison")
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->back('first');
        $this->prepareQuery();
    }
}

?>