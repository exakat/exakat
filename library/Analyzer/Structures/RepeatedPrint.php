<?php

namespace Analyzer\Structures;

use Analyzer;

class RepeatedPrint extends Analyzer\Analyzer {
    public function analyze() {
        // first one in sequence
        $this->atomIs("Functioncall")
            // echo and print are processed as identical
             ->tokenIs(array('T_PRINT', 'T_ECHO'))
             ->is('rank', 0)
             ->nextSibling()
             ->atomIs('Functioncall')
             ->tokenIs(array('T_PRINT', 'T_ECHO'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->tokenIs(array('T_PRINT', 'T_ECHO'))
             ->isNot('rank', 0)
             ->nextSibling()
             ->atomIs('Functioncall')
             ->tokenIs(array('T_PRINT', 'T_ECHO'))
             ->back('first')
             ->previousSibling()
             ->atomIs("Functioncall")
             ->tokenIsNot(array('T_PRINT', 'T_ECHO'))
             ->back('first');
        $this->prepareQuery();

    }
}

?>
