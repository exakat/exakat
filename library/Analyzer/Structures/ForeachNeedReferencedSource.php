<?php

namespace Analyzer\Structures;

use Analyzer;

class ForeachNeedReferencedSource extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Foreach")
             ->outIs('SOURCE')
             ->atomIsNot(array('Variable', 'Array', 'Staticproperty', 'Property'))
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->is('reference', 'true')
             ->back('first');
        $this->prepareQuery();
    }
}

?>