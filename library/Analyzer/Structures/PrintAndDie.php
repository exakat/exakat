<?php

namespace Analyzer\Structures;

use Analyzer;

class PrintAndDie extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_PRINT', 'T_ECHO'))
             ->nextSiblings()
             ->tokenIs(array('T_DIE', 'T_EXIT'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
