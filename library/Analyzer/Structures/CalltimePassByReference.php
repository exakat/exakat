<?php

namespace Analyzer\Structures;

use Analyzer;

class CalltimePassByReference extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Functioncall")
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('reference');
    }
}

?>