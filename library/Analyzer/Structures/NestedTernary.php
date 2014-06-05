<?php

namespace Analyzer\Structures;

use Analyzer;

class NestedTernary extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Ternary')
             ->atomInside('Ternary')
             ->back('first');
        $this->prepareQuery();
    }
}

?>