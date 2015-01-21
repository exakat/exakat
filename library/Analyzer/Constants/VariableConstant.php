<?php

namespace Analyzer\Constants;

use Analyzer;

class VariableConstant extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Functioncall')
             ->code('constant')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT');
        $this->prepareQuery();
    }
}

?>
