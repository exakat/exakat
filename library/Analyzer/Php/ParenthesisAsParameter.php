<?php

namespace Analyzer\Php;

use Analyzer;

class ParenthesisAsParameter extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
