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
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();
        
        $this->atomIs("While")
             ->outIs('CONDITION')
             ->atomIs(array('Variable', 'Functioncall'))
             ->savePropertyAs('code', 'condition')
             ->back('first')
             ->raw('filter{ it.out("BLOCK").out().loop(1){true}{it.object.atom == "Variable"}.has("code", condition).filter{it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any() }.any() == false }');
        $this->prepareQuery();

        $this->atomIs("Ifthen")
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Ternary")
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("For")
             ->outIs(array('FINAL', 'INCREMENT'))
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();
        
/*
    One of the variable inside the condition should be modified at some point : in the condition, or in the loop.

    Function calls are kept, but they should be characterized as non-stochastic 
    (calling with the same arguments may yield different result, such as random or fread)
*/
    }
}

?>