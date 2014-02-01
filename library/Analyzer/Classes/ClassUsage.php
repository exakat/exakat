<?php

namespace Analyzer\Classes;

use Analyzer;

class ClassUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("New")
             ->outIs('NEW');
        $this->prepareQuery();
        
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->outIs('CLASS');
        $this->prepareQuery();
    }
}

?>