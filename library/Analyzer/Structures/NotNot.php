<?php

namespace Analyzer\Structures;

use Analyzer;

class NotNot extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Not")
             ->outIs('NOT')
             ->atomIs('Not')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Not")
             ->outIs('NOT')
             ->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIs('Not')
             ->back('first');
        $this->prepareQuery();
    }
}

?>