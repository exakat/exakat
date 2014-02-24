<?php

namespace Analyzer\Classes;

use Analyzer;

class EmptyClass extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->atomIs('Void')
             ->back('first');
    }
}

?>