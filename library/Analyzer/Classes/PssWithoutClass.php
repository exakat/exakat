<?php

namespace Analyzer\Classes;

use Analyzer;

class PssWithoutClass extends Analyzer\Analyzer {
    public function analyze() {
        // new pss()
        $this->atomIs('New')
             ->outIs('NEW')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();

        // pss::$property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();

        // pss::method
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();

        // pss::constant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
