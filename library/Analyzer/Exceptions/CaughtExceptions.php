<?php

namespace Analyzer\Exceptions;

use Analyzer;

class CaughtExceptions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Catch")
             ->outIs('CLASS');
    }
}

?>
