<?php

namespace Analyzer\Arrays;

use Analyzer;

class Multidimensional extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Array")
             ->outIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->inIsnot('VARIABLE')
             ->back('first')
             ;
    }
}

?>