<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyUsedInternally extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('CODE')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->savePropertyAs('propertyname', 'propertyname')
             ->_as('ppp')
             ->outIs('VALUE')
             ->atomIs('Void')
             ->inIs('VALUE')
             ->inIs('ELEMENT')
             ->atomInside('Property')
             ->outIs('PROPERTY')
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
    }
}

?>