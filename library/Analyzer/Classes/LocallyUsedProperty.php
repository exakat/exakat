<?php

namespace Analyzer\Classes;

use Analyzer;

class LocallyUsedProperty extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->isNot('propertyname', null)
             ->savePropertyAs('propertyname', 'property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
