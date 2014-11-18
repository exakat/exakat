<?php

namespace Analyzer\Structures;

use Analyzer;

class ConstantConditions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Variables\\IsModified");
    }
    
    public function analyze() {
        $this->atomIs("While")
             ->outIs('CONDITION')
             ->atomIsNot('Variable')
             ->noAtomInside('Variable')
             ->back('first');
        $this->prepareQuery();
        
        $this->atomIs("While")
             ->outIs('CONDITION')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'condition')
             ->back('first')
             ->raw('filter{ it.out("BLOCK").out().loop(1){true}{it.object.atom == "Variable"}.has("code", condition).filter{it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any() }.any() == false }');
        $this->prepareQuery();

        $this->atomIs("Ifthen")
             ->outIs('CONDITION')
             ->atomIsNot('Variable')
             ->noAtomInside('Variable')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Ternary")
             ->outIs('CONDITION')
             ->atomIsNot('Variable')
             ->noAtomInside('Variable')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("For")
             ->outIs(array('FINAL', 'INCREMENT'))
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside('Variable')
             ->back('first');
        $this->prepareQuery();
        
/*
        One of the variable inside the condition should be modified at some point
        $this->atomIs("While")
             ->outIs('CONDITION')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'condition')
             ->back('first')
             ->raw('filter{ it.out("BLOCK").out().loop(1){true}{it.object.atom == "Variable"}.has("code", condition).filter{it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any() }.any() == false }');
        $this->prepareQuery();
*/

/*
    What about $i++ ? 
    
    What about functions calls? Only $this will be there.. May be 'methods' that change the $this or not.

    // add test for Do...While, for().
*/
    }
}

?>