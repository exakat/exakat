<?php

namespace Analyzer\Structures;

use Analyzer;

class Iffectation extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function analyze() {
        $this->atomIs("Ifthen")
             ->outIs('CONDITION')
             ->atomInside('Assignation')
             ->hasNoIn('ARGUMENT');
        $this->prepareQuery();
    }
}

?>