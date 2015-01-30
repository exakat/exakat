<?php

namespace Analyzer\Classes;

use Analyzer;

class DynamicClass extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->atomIs(array('Variable', 'Array', 'Property', 'Staticproperty'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->atomIs(array('Variable', 'Array', 'Property', 'Staticproperty'))
             ->back('first');
        $this->prepareQuery();
        
        // for constants... should check constant() function or Reflexion
    }
}

?>
