<?php

namespace Analyzer\Structures;

use Analyzer;

class SwitchWithMultipleDefault extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Switch")
             ->raw('filter{ it.out("CASES").out("CODE").out("ELEMENT").count() > 1}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>