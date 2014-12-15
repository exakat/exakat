<?php

namespace Analyzer\Classes;

use Analyzer;

class DynamicNew extends Analyzer\Analyzer {
    public function analyze() {
        // new $y->d();
        $this->atomIs("New")
             ->outIs('NEW')
             ->atomIs(array('Staticproperty', 'Property', 'Array'))
             ->back('first');
        $this->prepareQuery();

        // new $y();
        $this->atomIs("New")
             ->outIs('NEW')
             ->atomIs('Functioncall')
             ->outIs('NAME')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();
        
        // staticconstant is not possible
    }
}

?>
