<?php

namespace Analyzer\Structures;

use Analyzer;

class UnusedGlobal extends Analyzer\Analyzer {
    public function analyze() {
        // global in a function
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->savePropertyAs('id', 'theGlobalId')
             ->goToFunction()
             ->raw('filter{ it.out("BLOCK").out.loop(1){true}{it.object.atom == "Variable"}.has("code", theGlobal).hasNot("id", theGlobalId).any() == false}')
             ->back('result');
        $this->prepareQuery();

        // global in the global space
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->savePropertyAs('id', 'theGlobalId')
             ->notInFunction()
             ->goToFile()
             ->raw('filter{ it.out("FILE").out("ELEMENT").out("CODE").out.loop(1){!(it.object.atom in ["Class", "Function", "Trait", "Interface"])}{it.object.atom == "Variable"}.has("code", theGlobal).hasNot("id", theGlobalId).any() == false}')
             ->raw('filter{ it.out("FILE").out("CODE").out.loop(1){!(it.object.atom in ["Class", "Function", "Trait", "Interface"])}{it.object.atom == "Variable"}.has("code", theGlobal).hasNot("id", theGlobalId).any() == false}')
             ->back('result');
        $this->prepareQuery();

    }
}

?>
