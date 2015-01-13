<?php

namespace Analyzer\Structures;

use Analyzer;

class YodaComparison extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        $literals = array('String', 'Integer', 'Real', 'Boolean', 'Null', 'Identifier', 'Nsname');
        
        $this->atomIs('Comparison')
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
