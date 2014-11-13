<?php

namespace Analyzer\Interfaces;

use Analyzer;

class InterfaceUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Class')
             ->outIs('IMPLEMENTS')
             ->atomIs(array('Identifier', 'Nsname'));
        $this->prepareQuery();
        
        $this->atomIs('Instanceof')
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs('Typehint')
             ->outIs('CLASS');
        $this->prepareQuery();

        $this->atomIs('Use')
             ->outIs('USE');
        $this->prepareQuery();
    }
}

?>