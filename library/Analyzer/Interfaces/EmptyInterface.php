<?php

namespace Analyzer\Interfaces;

use Analyzer;

class EmptyInterface extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Interface")
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->atomIs('Void')
             ->back('first');
    }
}

?>