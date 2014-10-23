<?php

namespace Analyzer\Php;

use Analyzer;

class debugInfoUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->code('__debugInfo')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs('Class')
             ->back('first');
        $this->prepareQuery();
    }
}

?>