<?php

namespace Analyzer\Structures;

use Analyzer;

class Break0 extends Analyzer\Analyzer {
    protected $phpversion = '5.3';
    
    public function analyze() {
        $this->atomIs("Break")
             ->outIs('LEVEL')
             ->atomIs('Integer')
             ->code(0);
    }
}

?>