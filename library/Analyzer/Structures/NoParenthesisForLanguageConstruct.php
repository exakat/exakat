<?php

namespace Analyzer\Structures;

use Analyzer;

class NoParenthesisForLanguageConstruct extends Analyzer\Analyzer {
    public function analyze() {
        // inclusions
        $this->atomIs('Include')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();

        // throw
        $this->atomIs('Throw')
             ->outIs('THROW')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();

        // Return
        $this->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();

        // print, echo
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_PRINT', 'T_ECHO'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
