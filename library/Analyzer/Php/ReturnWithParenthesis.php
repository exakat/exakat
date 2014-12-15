<?php

namespace Analyzer\Php;

use Analyzer;

class ReturnWithParenthesis extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Return")
             ->outIs('RETURN')
             ->atomIs('Parenthesis')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
