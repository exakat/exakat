<?php

namespace Analyzer\Functions;

use Analyzer;

class KillsApp extends Analyzer\Analyzer {
    public function analyze() {
        // first round : only die and exit
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_DIE', 'T_EXIT', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();

        // second round
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->functionDefinition()
             ->analyzerIs('Analyzer\\Functions\\KillsApp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>