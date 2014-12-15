<?php

namespace Analyzer\Structures;

use Analyzer;

class EchoArguments extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->fullnspath('\\echo')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
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
