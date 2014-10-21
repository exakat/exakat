<?php

namespace Analyzer\Structures;

use Analyzer;

class EmptyWithExpression extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Assignation")
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSiblings()
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->fullnspath('\\empty')
             ->samePropertyAs('code', 'storage')
             ->back('first');
        $this->prepareQuery();
    }
}

?>