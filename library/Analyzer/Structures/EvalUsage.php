<?php

namespace Analyzer\Structures;

use Analyzer;

class EvalUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->code('eval', false);
        $this->prepareQuery();
    }
}

?>