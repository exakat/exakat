<?php

namespace Analyzer\Structures;

use Analyzer;

class VardumpUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->code(array('var_dump', 'print_r'), false);
        $this->prepareQuery();
    }
}

?>