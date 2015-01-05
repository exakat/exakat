<?php

namespace Analyzer\Structures;

use Analyzer;

class PrintWithoutParenthesis extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIs('T_PRINT')
             ->is('parenthesis', 'false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
