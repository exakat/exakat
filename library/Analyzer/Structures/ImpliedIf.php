<?php

namespace Analyzer\Structures;

use Analyzer;

class ImpliedIf extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Logical')
             ->code(array('or', '||', 'and', '&&'))
             ->inIsIE('CODE')
             ->hasIn('ELEMENT') 
             ->back('first');
        $this->prepareQuery();
    }
}

?>