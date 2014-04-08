<?php

namespace Analyzer\Structures;

use Analyzer;

class BreakNonInteger extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->atomIs("Break")
             ->outIs('LEVEL')
             ->atomIsnot(array('Integer', 'Void'))
             ->codeIsPositiveInteger();
    }
}

?>