<?php

namespace Analyzer\Structures;

use Analyzer;

class BreakNonInteger extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Break")
             ->outIs('LEVEL')
             ->atomIsnot(array('Integer', 'Void'));
    }
}

?>