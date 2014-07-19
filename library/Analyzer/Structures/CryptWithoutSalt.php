<?php

namespace Analyzer\Structures;

use Analyzer;

class CryptWithoutSalt extends Analyzer\Analyzer {
    public $phpversion = "5.6-";
    
    public function analyze() {
        $this->atomIs('Functioncall')
             ->code('crypt')
             ->hasNoIn('METHOD')
             ->outIs('ARGUMENTS')
             ->noChildWithOrder('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();
    }
}

?>