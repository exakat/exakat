<?php

namespace Analyzer\Structures;

use Analyzer;

class Noscream extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Noscream");
        $this->prepareQuery();
    }
}

?>