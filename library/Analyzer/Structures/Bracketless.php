<?php

namespace Analyzer\Structures;

use Analyzer;

class Bracketless extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Ifthen")
             ->outIs('THEN')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Ifthen")
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("For")
             ->outIs('BLOCK')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Foreach")
             ->outIs('BLOCK')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("While")
             ->outIs('BLOCK')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();
    }
}

?>