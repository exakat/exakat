<?php

namespace Analyzer\Structures;

use Analyzer;

class VariableGlobal extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->tokenIs('T_DOLLAR')
             ->isNot('bracket', true)
             ->outIs('NAME')
             ->atomIs('Property')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
