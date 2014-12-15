<?php

namespace Analyzer\Structures;

use Analyzer;

class AddZero extends Analyzer\Analyzer {
    public function analyze() {
        // $x += 0
        $this->atomIs("Assignation")
             ->code(array('+=', '-='))
             ->outIs('RIGHT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();

        // 0 + 2 
        $this->atomIs("Addition")
             ->tokenIs('T_PLUS')
             ->outIs('LEFT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();

        // $x +- 2
        $this->atomIs("Addition")
             ->outIs('RIGHT')
             ->code('0')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
