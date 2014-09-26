<?php

namespace Analyzer\Structures;

use Analyzer;

class MultipleCatch extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Try")
             ->isMore('count', 1)
             ->back('first');
        $this->prepareQuery();
    }
}

?>