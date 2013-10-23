<?php

namespace Analyzer\Structures;

use Analyzer;

class Iffectation extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Ifthen")
             ->out('CONDITION')
             ->atomInside('Assignation');
//        $this->printQuery();
        $this->prepareQuery();
    }
}

?>