<?php

namespace Analyzer\Structures;

use Analyzer;

class NotNot extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Not")
             ->out('NOT')
             ->atomIs('Not');
        $this->prepareQuery();

        $this->atomIs("Not")
             ->out('NOT')
             ->atomIs('Parenthesis')
             ->out('CODE')
             ->atomIs('Not');
        $this->prepareQuery();
    }
}

?>