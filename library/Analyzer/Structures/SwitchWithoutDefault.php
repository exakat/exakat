<?php

namespace Analyzer\Structures;

use Analyzer;

class SwitchWithoutDefault extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Switch")
             ->outIs('CASES')
             ->noAtomInside('Default')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
