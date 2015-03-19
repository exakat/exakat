<?php

namespace Analyzer\Php;

use Analyzer;

class ClassConstWithArray extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Const')
             ->outIs('VALUE')
             ->tokenIs(array('T_OPEN_BRACKET', 'T_ARRAY'))
             ->back('first')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs(array('Class', 'Interface'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
