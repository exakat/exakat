<?php

namespace Analyzer\Functions;

use Analyzer;

class UselessReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->code(array('__constructor', '__destructor', '__clone', '__unset'))
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
