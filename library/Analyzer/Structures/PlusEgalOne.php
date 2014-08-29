<?php

namespace Analyzer\Structures;

use Analyzer;

class PlusEgalOne extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Assignation")
             ->code(array('+=', '-='))
             ->outIs('RIGHT')
             ->code(1)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Assignation")
             ->code('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'A')
             ->back('first')
             ->outIs('RIGHT')
             ->atomIs('Addition')
             ->_as('B')
             ->outIs('LEFT')
             ->code(1)
             ->back('B')
             ->outIs('RIGHT')
             ->samePropertyAs('fullcode', 'A')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Assignation")
             ->code('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'A')
             ->back('first')
             ->outIs('RIGHT')
             ->atomIs('Addition')
             ->_as('B')
             ->outIs('RIGHT')
             ->code(1)
             ->back('B')
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'A')
             ->back('first');
        $this->prepareQuery();
    }
}

?>