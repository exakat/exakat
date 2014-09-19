<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyUsedInternally extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->_as('ppp')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Property')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();
    }
}

?>