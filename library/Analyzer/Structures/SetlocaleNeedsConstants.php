<?php

namespace Analyzer\Structures;

use Analyzer;

class SetlocaleNeedsConstants extends Analyzer\Analyzer {
    public function analyze() {
        $allowedConstants = array('\\LC_ALL', 
                                  '\\LC_COLLATE',
                                  '\\LC_CTYPE',
                                  '\\LC_MONETARY',
                                  '\\LC_NUMERIC',
                                  '\\LC_TIME',
                                  '\\LC_MESSAGES');

        // something else than a constant
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->fullnspath('\\setlocale')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs(array('String', 'Heredoc', 'Concatenation'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->fullnspath('\\setlocale')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIsNot($allowedConstants)
             ->back('first');
        $this->prepareQuery();    }
}

?>
