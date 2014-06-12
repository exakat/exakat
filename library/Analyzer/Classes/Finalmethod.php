<?php

namespace Analyzer\Classes;

use Analyzer;

class Finalmethod extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->atomInside('Function')
             ->_as('f')
             ->outIs('FINAL')
             ->back('f');
    }
}

?>