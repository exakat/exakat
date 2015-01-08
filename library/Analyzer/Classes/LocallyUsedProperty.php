<?php

namespace Analyzer\Classes;

use Analyzer;

class LocallyUsedProperty extends Analyzer\Analyzer {
    
    public function analyze() {
        // normal property
        $this->atomIs('Ppp')
             ->isNot('propertyname', null)
             ->hasNoOut('STATIC')
             ->savePropertyAs('propertyname', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ppp')
             ->isNot('propertyname', null)
             ->hasOut('STATIC')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
