<?php

namespace Analyzer\Structures;

use Analyzer;

class ForWithFunctioncall extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("For")
             ->out('FINAL')
             ->atomInside('Functioncall')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("For")
             ->out('INCREMENT')
             ->atomInside('Functioncall')
             ->back('first');
        $this->prepareQuery();
    }
}

?>