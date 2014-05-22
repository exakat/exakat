<?php

namespace Analyzer\Traits;

use Analyzer;

class TraitMethod extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Trait")
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ;
    }
}

?>