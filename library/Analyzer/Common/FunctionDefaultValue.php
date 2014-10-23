<?php

namespace Analyzer\Common;

use Analyzer;

class FunctionDefaultValue extends Analyzer\Analyzer {
    protected $rank = -1; // -1 will prevent rank to be found
    
    public function analyze() {
        $this->atomIs('Functioncall')
             ->code($this->code)
             ->hasNoIn('METHOD')
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', $this->rank)
             ->back('first');
        $this->prepareQuery();
    }
}

?>