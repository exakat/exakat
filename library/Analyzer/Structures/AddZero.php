<?php

namespace Analyzer\Structures;

use Analyzer;

class AddZero extends Analyzer\Analyzer {
    protected $severity  = Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = Analyzer\Analyzer::T_INSTANT;
    
    public function analyze() {
        $this->atomIs("Assignation")
             ->code(array('+=', '-='))
             ->outIs('RIGHT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Addition")
             ->outIs('LEFT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Multiplication")
             ->outIs('RIGHT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();
    }
}

?>