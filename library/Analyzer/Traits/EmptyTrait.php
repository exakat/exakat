<?php

namespace Analyzer\Traits;

use Analyzer;

class EmptyTrait extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Trait")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
    }
}

?>