<?php

namespace Analyzer\Classes;

use Analyzer;

class MakeDefault extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Assignation')
             ->code('=')
             ->outIs('RIGHT')
             ->atomIs(array('String', 'Integer', 'Boolean', 'Real'))
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Property')
             ->_as('result')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'propriete')
             
             // search for PPP now
             
             ->back('result');
//        $this->printQuery();
        $this->prepareQuery();
    }
}

?>
