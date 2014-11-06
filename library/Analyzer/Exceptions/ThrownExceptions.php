<?php

namespace Analyzer\Exceptions;

use Analyzer;

class ThrownExceptions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Throw")
             ->outIs('THROW');
        $this->prepareQuery();
    }
}

?>