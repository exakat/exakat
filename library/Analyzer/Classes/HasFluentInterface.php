<?php

namespace Analyzer\Classes;

use Analyzer;

class HasFluentInterface extends Analyzer\Analyzer {
    public function analyzer() {
        return array('HasNotFluentInterface');
    }
    
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs('RETURN')
             ->atomIs('Variable')
             ->code('$this')
             ->back('first');
        $this->prepareQuery();
    }
}

?>