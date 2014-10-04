<?php

namespace Analyzer\Classes;

use Analyzer;

class MethodDefinition extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs(array("Class", 'Trait'))
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME');
        $this->prepareQuery();

        $this->atomIs(array("Class", 'Trait'))
             ->outIs('BLOCK')
             ->atomIs('Function')
             ->outIs('NAME');
        $this->prepareQuery();
    }
}

?>