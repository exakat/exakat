<?php

namespace Analyzer\Constants;

use Analyzer;

class Constantnames extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('define', false)
             ->inIsnt('METHOD')
             ->outIs('ARGUMENTS')
             ->orderIs('ARGUMENT', 0);
    }
}

?>