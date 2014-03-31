<?php

namespace Analyzer\Structures;

use Analyzer;

class NotNot extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->atomIs("Not")
             ->outIs('NOT')
             ->atomIs('Not')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Not")
             ->outIs('NOT')
             ->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIs('Not')
             ->back('first');
        $this->prepareQuery();
    }
}

?>