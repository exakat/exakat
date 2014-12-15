<?php

namespace Analyzer\Php;

use Analyzer;

class LogicalInLetters extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Logical")
             ->code(array('and', 'or', 'xor'));
        $this->prepareQuery();
    }
}

?>