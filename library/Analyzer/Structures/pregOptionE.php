<?php

namespace Analyzer\Structures;

use Analyzer;

class pregOptionE extends Analyzer\Analyzer {
    public function analyze() {
        // preg_match with a string
        $this->atomIs('Functioncall')
             ->fullnspath('\preg_replace')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->regex('noDelimiter', '^([~/|`%#\\$!,@\\\\{\\\\(\\\\[]).*?([~/|`%#\\$!,@\\\\}\\\\)\\\\]])(.*e.*)\\$') //
             ->back('first');
        $this->prepareQuery();

        // With a concatenation
        $this->atomIs('Functioncall')
             ->fullnspath('\preg_replace')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs('T_QUOTE')
             ->regex('fullcode', '^.([~/|`%#\\$!,@\\\\{\\\\(\\\\[]).*?([~/|`%#\\$!,@\\\\}\\\\)\\\\]])(.*e.*)*.\\$') //
             ->back('first');
        $this->prepareQuery();

        // with a string 
        $this->atomIs('Functioncall')
             ->fullnspath('\preg_replace')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->tokenIs('T_DOT')
             ->regex('fullcode', '^.([~/|`%#\\$!,@\\\\{\\\\(\\\\[]).*?([~/|`%#\\$!,@\\\\}\\\\)\\\\]])(.*e.*).\\$') //
             ->back('first');
        $this->prepareQuery();
// Actual letters used for Options in PHP imsxeuADSUXJ (others may yield an error)
    }
}

?>
