<?php

namespace Analyzer\Classes;

use Analyzer;

class HasMagicProperty extends Analyzer\Analyzer {
    public function analyze() {
        // Nsname that is not used somewhere else
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->atomInside('Function')
             ->outIs('NAME')
             ->code(array('__get', '__set'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>