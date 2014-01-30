<?php

namespace Analyzer\Constants;

use Analyzer;

class Constantnames extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('define', false)
             ->inIsnot('METHOD')
             ->out('ARGUMENTS')
             ->orderIs('ARGUMENT', 0);
//        $this->printQuery();
    }
}

?>