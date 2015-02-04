<?php

namespace Analyzer\Structures;

use Analyzer;

class EchoArguments extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\echo')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\echo')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('String')
             ->hasOut('CONTAIN')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
