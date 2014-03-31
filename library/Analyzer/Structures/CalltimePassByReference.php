<?php

namespace Analyzer\Structures;

use Analyzer;

class CalltimePassByReference extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_SLOW;

    public function analyze() {
        $this->atomIs("Functioncall")
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('reference');
    }
}

?>