<?php

namespace Analyzer\Functions;

use Analyzer;

class EmptyFunction extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->atomIs('Void')
             ->back('first');
    }
}

?>