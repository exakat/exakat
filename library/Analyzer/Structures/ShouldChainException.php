<?php

namespace Analyzer\Structures;

use Analyzer;

class ShouldChainException extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // omitted 3rd argument
        $this->atomIs('Catch')
             ->outIs('CODE')
             ->atomInside('Throw')
             ->outIs('THROW')
             ->outIs('NEW')
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 2)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'caught')
             ->inIs('VARIABLE')
             ->outIs('CODE')
             ->atomInside('Throw')
             ->outIs('THROW')
             ->outIs('NEW')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 2)
             ->notSamePropertyAs('code', 'caught')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
