<?php

namespace Analyzer\Classes;

use Analyzer;

class MethodDefinition extends Analyzer\Analyzer {

    public function analyze() {
        // class with one method only
        $this->atomIs(array('Class', 'Trait'))
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME');
        $this->prepareQuery();
    }
}

?>
