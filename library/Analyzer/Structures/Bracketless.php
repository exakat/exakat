<?php

namespace Analyzer\Structures;

use Analyzer;

class Bracketless extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Ifthen")
             ->isNot('alternative', 'true')
             ->outIs('THEN')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Ifthen")
             ->isNot('alternative', 'true')
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->is('bracket', null)
             ->raw('filter{ (it.out("ELEMENT").count() != 1) || (it.out("ELEMENT").has("atom", "Ifthen").any() == false)}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("For")
             ->isNot('alternative', 'true')
             ->outIs('BLOCK')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Foreach")
             ->isNot('alternative', 'true')
             ->outIs('BLOCK')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("While")
             ->isNot('alternative', 'true')
             ->outIs('BLOCK')
             ->is('bracket', null)
             ->back('first');
        $this->prepareQuery();
    }
}

?>