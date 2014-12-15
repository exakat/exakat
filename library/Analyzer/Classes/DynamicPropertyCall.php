<?php

namespace Analyzer\Classes;

use Analyzer;

class DynamicPropertyCall extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Property")
             ->outIs('PROPERTY')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('PROPERTY')
             ->atomIs(array('Variable', 'Array'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
