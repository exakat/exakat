<?php

namespace Analyzer\Functions;

use Analyzer;

class MultipleSameArguments extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw("filter{ it.out('ARGUMENTS').out('ARGUMENT').aggregate().groupCount(){if (it.atom == 'Typehint') { it.out('VARIABLE').next().code; } else if (it.atom == 'Assignation') { it.out('LEFT').next().code; } else {it.code; }}.cap.next().findAll{it.value > 1}.any() }");
        $this->prepareQuery();
    }
}

?>