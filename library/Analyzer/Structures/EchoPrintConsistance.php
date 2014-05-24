<?php

namespace Analyzer\Structures;

use Analyzer;

class EchoPrintConsistance extends Analyzer\Analyzer {

    public function analyze() {
        
        $this->atomIs("Functioncall")
             ->code(array('echo', 'print'))
             ->groupFilter("x2 = it.code", 10 / 100);
    }
}

?>