<?php

namespace Analyzer\Structures;

use Analyzer;

class TryFinally extends Analyzer\Analyzer {    
    public static $phpversion = '5.5+';
    
    public function analyze() {
        $this->atomIs("Try")
             ->outIs('CATCH')
             ->atomIs('Finally')
             ->back('first');
        $this->prepareQuery();
    }
}

?>