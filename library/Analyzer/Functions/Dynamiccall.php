<?php

namespace Analyzer\Functions;

use Analyzer;

class Dynamiccall extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->outIs('NAME')
             ->atomIs(array('Array', 'Variable'));
        $this->prepareQuery();
    }
}

?>
