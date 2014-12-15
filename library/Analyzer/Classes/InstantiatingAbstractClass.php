<?php

namespace Analyzer\Classes;

use Analyzer;

class InstantiatingAbstractClass extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->atomIs('Functioncall')
             ->classDefinition()
             ->hasOut('ABSTRACT')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
