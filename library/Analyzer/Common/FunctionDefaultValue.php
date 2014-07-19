<?php

namespace Analyzer\Common;

use Analyzer;

class FunctionDefaultValue extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->code($this->code)
             ->hasNoIn('METHOD')
             ->outIs('ARGUMENTS')
             ->noChildWithOrder('ARGUMENT', $this->order)
             ->back('first');
        $this->prepareQuery();
    }
}

?>