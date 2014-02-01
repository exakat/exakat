<?php

namespace Analyzer\Structures;

use Analyzer;

class ForWithFunctioncall extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("For")
             ->outIs('FINAL')
             ->atomInside('Functioncall')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("For")
             ->outIs('INCREMENT')
             ->atomInside('Functioncall')
             ->back('first');
        $this->prepareQuery();
    }
}

?>