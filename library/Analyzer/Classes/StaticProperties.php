<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticProperties extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->_as('ppp')
             ->outIs('STATIC')
             ->back('ppp');
    }
}

?>