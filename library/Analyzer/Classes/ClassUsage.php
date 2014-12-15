<?php

namespace Analyzer\Classes;

use Analyzer;

class ClassUsage extends Analyzer\Analyzer {

    public function analyze() {
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

        $this->atomIs("Catch")
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs("Typehint")
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs("Instanceof")
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs("Class")
             ->outIs(array('EXTENDS', 'IMPLEMENTS'));
        $this->prepareQuery();

        $this->atomIs("Use")
             ->outIs('USE');
        $this->prepareQuery();
    }
}

?>
