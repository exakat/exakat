<?php

namespace Analyzer\Variables;

use Analyzer;

class LostReferences extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Variable')
             ->is('reference', 'true')
             ->savePropertyAs('code', 'parameter')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->outIs('BLOCK')
             ->atomInside('Assignation')
             ->outIs('LEFT')
             ->samePropertyAs('code', 'parameter')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->is('reference', 'true')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
