<?php

namespace Analyzer\Structures;

use Analyzer;

class DereferencingAS extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIs('Functioncall') // or some array-returning function
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->fullnspath('\\array')
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSiblings()
             ->atomInside('Variable')
             ->samePropertyAs('code', 'storage')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();

        $this->atomIs("Assignation")
             ->outIs('RIGHT')
             ->atomIs('String') // or some array-returning function
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSiblings()
             ->atomInside('Variable')
             ->samePropertyAs('code', 'storage')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();
    }
}

?>
