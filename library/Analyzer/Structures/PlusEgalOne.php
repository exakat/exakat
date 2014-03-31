<?php

namespace Analyzer\Structures;

use Analyzer;

class PlusEgalOne extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

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
             ->savePropertyAs('code', 'A')
             ->back('first')
             ->outIs('RIGHT')
             ->_as('B')
             ->outIs('LEFT')
             ->code(1)
             ->back('B')
             ->outIs('RIGHT')
             ->samePropertyAs('code', 'A')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Assignation")
             ->code('=')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'A')
             ->back('first')
             ->outIs('RIGHT')
             ->_as('B')
             ->outIs('RIGHT')
             ->code(1)
             ->back('B')
             ->outIs('LEFT')
             ->samePropertyAs('code', 'A')
             ->back('first');
        $this->prepareQuery();
    }
}

?>