<?php

namespace Analyzer\Structures;

use Analyzer;

class AddZero extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Assignation")
             ->code(array('+=', '-='))
             ->outIs('RIGHT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Addition")
             ->outIs('LEFT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Multiplication")
             ->outIs('RIGHT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();
    }
}

?>