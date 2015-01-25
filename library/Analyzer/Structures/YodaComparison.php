<?php

namespace Analyzer\Structures;

use Analyzer;

class YodaComparison extends Analyzer\Analyzer {
    public function analyze() {
        $literals = array('String', 'Integer', 'Float', 'Boolean', 'Null', 'Identifier', 'Nsname');
        
        $this->atomIs('Comparison')
             ->code(array('==','==='))
             ->outIs('RIGHT')
             ->atomIs($literals)
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIsNot($literals)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
