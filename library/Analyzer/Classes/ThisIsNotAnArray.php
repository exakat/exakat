<?php

namespace Analyzer\Classes;

use Analyzer;

class ThisIsNotAnArray extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Variable")
             ->code('$this')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first');
    }
}

?>