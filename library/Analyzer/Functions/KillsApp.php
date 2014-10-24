<?php

namespace Analyzer\Functions;

use Analyzer;

class KillsApp extends Analyzer\Analyzer {
    public function analyze() {
        // first round : only die and exit
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->atomIs('Functioncall')
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();

        // second round
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->atomIs('Functioncall')
             ->functionDefinition()
             ->inIs('NAME')
             ->analyzerIs('Analyzer\\Functions\\KillsApp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>