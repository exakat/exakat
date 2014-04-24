<?php

namespace Analyzer\Structures;

use Analyzer;

class Noscream extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_INSTANT;

    public function analyze() {
        $this->atomIs("Noscream")
             ->outIs('AT')
             ->atomIs('Functioncall')
             ->codeIsNot('fopen')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Noscream")
             ->outIs('AT')
             ->atomIsNot('Functioncall')
             ->back('first');
        $this->prepareQuery();
    }
}

?>