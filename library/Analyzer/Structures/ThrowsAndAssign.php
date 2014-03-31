<?php

namespace Analyzer\Structures;

use Analyzer;

class ThrowsAndAssign extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->atomIs("Throw")
             ->outIs('THROW')
             ->atomIs('Assignation');
    }
}

?>