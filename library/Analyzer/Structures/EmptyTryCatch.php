<?php

namespace Analyzer\Structures;

use Analyzer;

class EmptyTryCatch extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Try")
             ->outIs('CATCH')
             ->outIs('CODE')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
