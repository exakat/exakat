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
             ->hasNoOut('STATIC')
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
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'fns')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->_as('ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'propertyname')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'fns')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE') // for arrays
             ->samePropertyAs('code','propertyname')
             ->back('ppp');
        $this->prepareQuery();
        
        // protected property
        
        // public property (fullnspath + ...)
    }
}

?>
