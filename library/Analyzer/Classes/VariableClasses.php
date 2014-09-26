<?php

namespace Analyzer\Classes;

use Analyzer;

class VariableClasses extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("New")
             ->outIs('NEW')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("New")
             ->outIs('NEW')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->back('first');
        $this->prepareQuery();
        
    }
}

?>