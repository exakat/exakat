<?php

namespace Analyzer\Structures;

use Analyzer;

class ThrowsAndAssign extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Throw")
             ->outIs('THROW')
             ->atomIs('Assignation');
    }
}

?>