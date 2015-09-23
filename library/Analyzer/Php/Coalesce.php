<?php

namespace Analyzer\Php;

use Analyzer;

class Coalesce extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Ternary')
             ->outIs('THEN')
             ->atomIs('TernaryElse')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
