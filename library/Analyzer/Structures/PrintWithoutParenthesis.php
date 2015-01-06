<?php

namespace Analyzer\Structures;

use Analyzer;

class PrintWithoutParenthesis extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIs('T_PRINT')
             ->is('parenthesis', 'true')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
