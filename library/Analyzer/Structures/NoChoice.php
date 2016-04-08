<?php

namespace Analyzer\Structures;

use Analyzer;

class NoChoice extends Analyzer\Analyzer {
    public function analyze() {
        // $a == 2 ? doThis() : doThis();
        $this->atomIs('Ternary')
             ->outIs('THEN')
             ->savePropertyAs('fullcode', 'cdt')
             ->inIs('THEN')
             ->outIs('ELSE')
             ->samePropertyAs('fullcode', 'cdt')
             ->back('first');
        $this->prepareQuery();

        // $a == 2 ? doThis() : doThis();
        $this->atomIs('Ternary')
             ->filter('it.out("THEN").has("code", ":").any()') // This is a ?:
             ->outIs('CONDITION')
             ->atomIs(array('Variable', 'Property', 'Staticproperty', 'Array'))
             ->savePropertyAs('fullcode', 'cdt')
             ->inIs('CONDITION')
             ->outIs('ELSE')
             ->atomIs(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'cdt')
             ->back('first');
        $this->prepareQuery();

        // if ($a == 2) Then doThis(); else doThis();
        $this->atomIs('Ifthen')
             ->outIs('THEN')
             ->atomIs('Sequence')
             ->is('count', 1)
             ->outIs('ELEMENT')
             ->savePropertyAs('fullcode', 'cdt')
             ->inIs('ELEMENT')
             ->inIs('THEN')
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->is('count', 1)
             ->outIs('ELEMENT')
             ->samePropertyAs('fullcode', 'cdt')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
