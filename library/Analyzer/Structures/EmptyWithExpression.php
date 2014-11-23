<?php

namespace Analyzer\Structures;

use Analyzer;

class EmptyWithExpression extends Analyzer\Analyzer {
    public function analyze() {
        // $a = 2; empty($a) ; in a row
        $this->atomIs("Assignation")
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSiblings()
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_EMPTY')
             ->fullnspath('\\empty')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('code', 'storage')
             ->back('first');
        $this->prepareQuery();
    }
}

?>