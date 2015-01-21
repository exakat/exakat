<?php

namespace Analyzer\Structures;

use Analyzer;

class CalltimePassByReference extends Analyzer\Analyzer {
    public $phpVersion = '5.4-';

    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIsNot('T_ARRAY')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('reference', 'true');
        $this->prepareQuery();
    }
}

?>
