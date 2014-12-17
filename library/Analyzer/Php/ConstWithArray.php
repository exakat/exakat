<?php

namespace Analyzer\Php;

use Analyzer;

class ConstWithArray extends Analyzer\Analyzer {

    public function analyze() {
        // method used in a static methodcall \a\b::b()
        $this->atomIs("Const")
             ->outIs('VALUE')
             ->atomIs('Functioncall')
             ->fullnspath('\\array')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
