<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticMethods extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('function')
             ->outIs('STATIC')
             ->back('function')
             ->outIs('NAME');
    }
}

?>