<?php

namespace Analyzer\Arrays;

use Analyzer;

class Phparrayindex extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Array")
             ->outIs('VARIABLE')
             ->code(VariablePhp::$variables)
             ->back('first');
        $this->prepareQuery();
    }
}

?>