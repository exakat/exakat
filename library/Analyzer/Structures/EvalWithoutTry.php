<?php

namespace Analyzer\Structures;

use Analyzer;

class EvalWithoutTry extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->fullnspath('\\eval')
             ->notInInstruction('Try')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
