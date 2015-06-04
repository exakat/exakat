<?php

namespace Analyzer\Structures;

use Analyzer;

class CouldUseShortAssignation extends Analyzer\Analyzer {
    public function analyze() {
        // Commutative operation
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->code(array('+', '*'))
             ->outIs(array('LEFT', 'RIGHT'))
             ->samePropertyAs('fullcode', 'receiver')
             ->back('first');
        $this->prepareQuery();

        // Non-Commutative operation
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->code(array('-', '/', '%', '<<=', '>>=', '**', '&', '^', '|'))
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'receiver')
             ->back('first');
        $this->prepareQuery();

        // Special case for .
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->is('rank', 0)
             ->samePropertyAs('fullcode', 'receiver')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
