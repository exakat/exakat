<?php

namespace Analyzer\Functions;

use Analyzer;

class IsGenerator extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->atomInside('Yield')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
