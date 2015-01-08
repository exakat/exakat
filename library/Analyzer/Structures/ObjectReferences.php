<?php

namespace Analyzer\Structures;

use Analyzer;

class ObjectReferences extends Analyzer\Analyzer {
    /* Remove this is useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        // f(stdclass &$x) 
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('VARIABLE')
             ->is('reference', 'true');
        $this->prepareQuery();

        // f(&$x) and $x->y(); 
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'variable')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'variable');
        $this->prepareQuery();

        // f(&$x) and $x->y; 
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'variable')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'variable');
        $this->prepareQuery();
        
        // todo : same with default value!
        
        // foreach($a as &$b) { $b->method;}
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->is('reference', 'true')
             ->savePropertyAs('code', 'variable')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'variable');
        $this->prepareQuery();

        // foreach($a as &$b) { $b->property;}
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->is('reference', 'true')
             ->savePropertyAs('code', 'variable')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'variable');
        $this->prepareQuery();
        
        // todo $x = new object; then &$x;
    }
}

?>
