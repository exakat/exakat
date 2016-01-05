<?php

namespace Analyzer\Structures;

use Analyzer;

class SwitchToSwitch extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Ifthen')
             ->hasNoIn('ELSE')
             ->outIs('ELSE')
             ->raw('transform{ if (it.atom == "Sequence" && it.count == 1) { it.out("ELEMENT").next(); } else { it; }}')
             ->atomIs('Ifthen')
             ->outIs('ELSE')
             ->raw('transform{ if (it.atom == "Sequence" && it.count == 1) { it.out("ELEMENT").next(); } else { it; }}')
             ->atomIs('Ifthen')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
