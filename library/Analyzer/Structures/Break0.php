<?php

namespace Analyzer\Structures;

use Analyzer;

class Break0 extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;
    
    protected $phpversion = '5.4-';
    
    public function analyze() {
        $this->atomIs("Break")
             ->outIs('LEVEL')
             ->atomIs('Integer')
             ->code(0)
             ->back('first');
    }
}

?>