<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticContainsThis extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('STATIC')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->code('$this', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
