<?php

namespace Analyzer\Structures;

use Analyzer;

class CatchShadowsVariable extends Analyzer\Analyzer {
    public function analyze() {
        // Catch inside a function
        $this->atomIs("Catch")
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'catchVariable')
             ->goToCurrentScope()
             ->outIs(array('BLOCK', 'CODE'))
             ->atomInside('Variable')
             ->samePropertyAs('code', 'catchVariable')
             ->hasNoIn('VARIABLE')
             ->isNotInCatchBlock()
             ->back('first');
        $this->prepareQuery();
    }
}

?>
