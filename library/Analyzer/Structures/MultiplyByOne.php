<?php

namespace Analyzer\Structures;

use Analyzer;

class MultiplyByOne extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_INSTANT;

    public function analyze() {
        $this->atomIs("Assignation")
             ->code(array('*=', '/='))
             ->outIs('RIGHT')
             ->code(1)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Multiplication")
             ->code("*")
             ->outIs('LEFT')
             ->code('1')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Multiplication")
             ->outIs('RIGHT')
             ->code('1')
             ->back('first');
        $this->prepareQuery();
    }
}

?>