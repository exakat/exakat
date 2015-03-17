<?php

namespace Analyzer\Security;

use Analyzer;

class ShouldUsePreparedStatement extends Analyzer\Analyzer {
    public function analyze() {
        $functions = array( '\\sqlite_query',
                            '\\mysql_query',
                            '\\mysqli_query',
                            '\\pg_query');
        
        // dynamic type in the code
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIs(array('Concatenation', 'Variable', 'Property'))
             /*
             ->isNot('constante', true)
             */
             ->back('first');
        $this->prepareQuery();

        // dynamic type in the code
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIs('String')
             ->outIs('CONTAIN')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
