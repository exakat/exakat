<?php

namespace Analyzer\Classes;

use Analyzer;

class ClassUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("New")
             ->out('NEW');
        $this->prepareQuery();
        
        $this->atomIs("Staticmethodcall")
             ->out('CLASS');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->out('CLASS');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->out('CLASS');
        $this->prepareQuery();
        
    }
}

?>