<?php

namespace Analyzer\Structures;

use Analyzer;

class Noscream extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Noscream");
        $this->prepareQuery();
    }
}

?>