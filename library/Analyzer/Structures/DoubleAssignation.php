<?php

namespace Analyzer\Structures;

use Analyzer;

class DoubleAssignation extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'name')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
