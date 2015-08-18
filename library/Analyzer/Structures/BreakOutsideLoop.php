<?php

namespace Analyzer\Structures;

use Analyzer;

class BreakOutsideLoop extends Analyzer\Analyzer {
    public function analyze() {
        // break (null)
        $this->atomIs('Break')
             ->outIs('LEVEL')
             ->atomIs('Void')
             ->filter('it.in.loop(1){true}{it.object.atom in ["Dowhile", "For", "Foreach", "While", "Switch"]}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // break 1
        $this->atomIs('Break')
             ->outIs('LEVEL')
             ->atomIs('Integer')
             ->savePropertyAs('code', 'counter')
             ->filter('it.in.loop(1){true}{ it.object.atom in ["Dowhile", "For", "Foreach", "While", "Switch"] }.count() < counter.toInteger()') // really count temps
             ->back('first');
        $this->prepareQuery();

        // continue (null)
        $this->atomIs('Continue')
             ->outIs('LEVEL')
             ->atomIs('Void')
             ->filter('it.in.loop(1){true}{it.object.atom in ["Dowhile", "For", "Foreach", "While", "Switch"]}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // continue 1
        $this->atomIs('Continue')
             ->outIs('LEVEL')
             ->atomIs('Integer')
             ->savePropertyAs('code', 'counter')
             ->filter('it.in.loop(1){true}{ it.object.atom in ["Dowhile", "For", "Foreach", "While", "Switch"] }.count() < counter.toInteger()') // really count temps
             ->back('first');
        $this->prepareQuery();
    }
}

?>
