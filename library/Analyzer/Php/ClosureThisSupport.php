<?php

namespace Analyzer\Php;

use Analyzer;

class ClosureThisSupport extends Analyzer\Analyzer {
    protected $phpversion = "5.4-";
    
    public function analyze() {
        $this->atomIs("Function")
             ->is('lambda', "'true'")
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->code('$this', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>