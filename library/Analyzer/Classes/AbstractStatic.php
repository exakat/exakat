<?php

namespace Analyzer\Classes;

use Analyzer;

class AbstractStatic extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->atomInside('Function')
             ->_as('f')
             ->outIs('ABSTRACT')
             ->back('f')
             ->outIs('STATIC')
             ->back('f')
             ;
    }
}

?>