<?php

namespace Analyzer\Structures;

use Analyzer;

class BreakNonInteger extends Analyzer\Analyzer {
    public $phpVersion = "5.4-";

    public function analyze() {
        $this->atomIs("Break")
             ->outIs('LEVEL')
             ->atomIsnot(array('Integer', 'Void'))
             ->codeIsPositiveInteger();
    }
}

?>