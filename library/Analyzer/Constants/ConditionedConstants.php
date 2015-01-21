<?php

namespace Analyzer\Constants;

use Analyzer;

class ConditionedConstants extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->fullnspath('\\define')
             ->raw('in.loop(1){true}{it.object.atom == "Ifthen"}')
             ->back('first')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0);
        $this->prepareQuery();
    }
}

?>
