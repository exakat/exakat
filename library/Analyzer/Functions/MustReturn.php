<?php

namespace Analyzer\Functions;

use Analyzer;

class MustReturn extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->hasNoOut('ABSTRACT')
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Interface").any() == false}')
             ->outIs('NAME')
             ->code(array('__call', '__callStatic', '__get', '__isset', '__sleep', '__toString', '__set_state', 
                          '__invoke', '__debugInfo'))
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->back('first')
             ->noAtomInside('Return');
        $this->prepareQuery();
    }
}

?>
