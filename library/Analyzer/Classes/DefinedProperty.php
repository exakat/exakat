<?php

namespace Analyzer\Classes;

use Analyzer;

class DefinedProperty extends Analyzer\Analyzer {

    public function analyze() {
        // locally defined
        $this->atomIs("Property")
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        // defined in parents (Extended)
        $this->atomIs("Property")
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->goToExtends()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        // defined in parents implemented
        $this->atomIs("Property")
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->goToImplements()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        // defined in traits (use)
        $this->atomIs("Property")
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->goToTraits()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

    }
}

?>