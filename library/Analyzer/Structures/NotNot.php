<?php

namespace Analyzer\Structures;

use Analyzer;

class NotNot extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Not")
             ->out('NOT')
             ->atomIs('Not');
        $this->prepareQuery();
    }
}

?>