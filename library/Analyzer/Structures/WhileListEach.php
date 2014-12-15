<?php

namespace Analyzer\Structures;

use Analyzer;

class WhileListEach extends Analyzer\Analyzer {
    public function analyze() {
        // while (list($a, $b) = each($c)) {}
        $this->atomIs("While")
             ->outIs('CONDITION')
             ->atomIs('Assignation')
             ->_as('assignation')
             ->outIs('LEFT')
             ->atomIs('Functioncall')
             ->code('list')
             ->back('assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->code('each')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
