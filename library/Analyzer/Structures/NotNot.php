<?php

namespace Analyzer\Structures;

use Analyzer;

class NotNot extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Not")
             ->outIs('NOT')
             ->atomIs('Not');
        $this->prepareQuery();

        $this->atomIs("Not")
             ->outIs('NOT')
             ->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIs('Not');
        $this->prepareQuery();
    }
}

?>