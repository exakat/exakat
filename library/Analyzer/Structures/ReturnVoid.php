<?php

namespace Analyzer\Structures;

use Analyzer;

class ReturnVoid extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Return")
             ->outIs('RETURN')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>