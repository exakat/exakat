<?php

namespace Analyzer\Classes;

use Analyzer;

class UseThis extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("MethodDefinition");
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->hasNoOut('STATIC')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->code('$this', true)
             ->back('first');
        $this->prepareQuery();

        // Case for statics 
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->outIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'classe')
             ->goToClass()
             ->samePropertyAs('fullnspath', 'classe')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->outIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'classe')
             ->goToClass()
             ->samePropertyAs('fullnspath', 'classe')
             ->back('first');
        $this->prepareQuery();

    // static constant are excluded. 
    }
}

?>
