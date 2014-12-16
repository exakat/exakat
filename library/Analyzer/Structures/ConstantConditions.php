<?php

namespace Analyzer\Structures;

use Analyzer;

class ConstantConditions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified',
                     'Analyzer\\Constants\\IsPhpConstant');
    }
    
    public function analyze() {

        $data = new \Data\Methods();
        $nonStochatichFunctions = $data->getNonStochasticFunctions();

        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->noAtomInside(array('Variable', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->back('first');
        $this->prepareQuery();
        
        $this->atomIs('While')
             ->outIs('CONDITION')
             ->atomIs(array('Variable', 'Functioncall'))
             ->codeIsNot($nonStochatichFunctions)
             ->savePropertyAs('code', 'condition')
             ->back('first')
             // variables are only read
             ->raw('filter{ it.out("BLOCK").out().loop(1){true}{it.object.atom == "Variable"}.has("code", condition).filter{it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any() }.any() == false }');
        $this->prepareQuery();

        $this->atomIs('Ifthen')
             // constant shouldn't be PHP's 
             ->raw('filter{it.out("CONDITION").out().loop(1){true}{it.object.atom in ["Identifier", "Nsname"]}.filter{it.in("ANALYZED").has("code", "Analyzer\\\\Constants\\\\IsPhpConstant").any() }.any() == false }')
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Ternary')
             // constant shouldn't be PHP's 
             ->raw('filter{it.out("CONDITION").out().loop(1){true}{it.object.atom in ["Identifier", "Nsname"]}.filter{it.in("ANALYZED").has("code", "Analyzer\\\\Constants\\\\IsPhpConstant").any() }.any() == false }')
             ->outIs('CONDITION')
             ->atomIsNot(array('Variable', 'Functioncall'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
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
