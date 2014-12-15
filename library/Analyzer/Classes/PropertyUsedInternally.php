<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyUsedInternally extends Analyzer\Analyzer {

    public function analyze() {
        // private property + $this->property
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->_as('ppp')
             ->hasOut('PRIVATE')
             ->savePropertyAs('propertyname', 'propertyname')
             ->inIs('ELEMENT')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();

        // private static property + class/static::property
        
        // protected property
        
        // public property (fullnspath + ...)
    }
}

?>
