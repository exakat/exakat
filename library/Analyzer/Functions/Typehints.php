<?php

namespace Analyzer\Functions;

use Analyzer;

class Typehints extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Typehint")
             ->outIs('CLASS');
    }
}

?>