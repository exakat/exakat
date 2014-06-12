<?php

namespace Analyzer\Interfaces;

use Analyzer;

class InterfaceMethod extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Interface")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ;
    }
}

?>