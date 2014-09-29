<?php

namespace Analyzer\Php;

use Analyzer;

class IsnullVsEqualNull extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->fullnspath('\\is_null');
        $this->prepareQuery();
    }
}

?>