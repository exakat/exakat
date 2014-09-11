<?php

namespace Analyzer\Classes;

use Analyzer;

class PssWithoutClass extends Analyzer\Analyzer {
    public function analyze() {
        // pss::$property
        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->hasNoClass()
             ->back('first');
        $this->prepareQuery();

        // pss::method
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->hasNoClass()
             ->back('first');
        $this->prepareQuery();

        // pss::constant
        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->hasNoClass()
             ->back('first');
        $this->prepareQuery();
    }
}

?>