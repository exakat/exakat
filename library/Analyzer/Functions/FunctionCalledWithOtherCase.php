<?php

namespace Analyzer\Functions;

use Analyzer;

class FunctionCalledWithOtherCase extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Functioncall")
             ->inIsnot('NEW')
             ->raw("filter{ x = it; g.V.has('atom', 'Function').out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.hasNot('code', it.code).any() }");
    }

}

?>