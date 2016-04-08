<?php

namespace Analyzer\Structures;

use Analyzer;

class CommonAlternatives extends Analyzer\Analyzer {
        public function analyze() {
        $this->atomIs('Ifthen')
             ->outIs('THEN')
             ->atomIs('Sequence')
             ->outIs('ELEMENT')
             ->_as('results')
             ->savePropertyAs('fullcode', 'expression')
             ->inIs('ELEMENT')
             ->inIs('THEN')
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->outIs('ELEMENT')
             ->samePropertyAs('fullcode', 'expression')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
