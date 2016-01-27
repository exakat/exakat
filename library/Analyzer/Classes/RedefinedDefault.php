<?php

namespace Analyzer\Classes;

use Analyzer;

class RedefinedDefault extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->outIs('DEFINE')
             ->savePropertyAs('propertyname', 'name')
             ->_as('results')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')

             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
// Not using atomInside, to avoid values in a condition
//             ->atomInside('Assignation') 
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Property')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'name')
             
             // sameParameterAs
             ->back('results');
        $this->prepareQuery();
    }
}

?>
