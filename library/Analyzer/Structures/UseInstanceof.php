<?php

namespace Analyzer\Structures;

use Analyzer;

class UseInstanceof extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // get_class($x) == 'Function'
        $this->atomIs('Comparison')
             ->outIs('LEFT')
             ->atomIs('Functioncall')
             ->fullnspath('\\get_class')
             ->back('first');
        $this->prepareQuery();

        // 'Function' == get_class($x)
        $this->atomIs('Comparison')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->fullnspath('\\get_class')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
