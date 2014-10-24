<?php

namespace Analyzer\Functions;

use Analyzer;

class MustReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->hasNoOut('ABSTRACT')
             ->outIs('NAME')
             ->code(array("__call", '__callStatic', '__get', '__isset', '__sleep', '__toString', '__set_state', 
                            '__invoke', '__debugInfo'))
             ->back('first')
             ->noAtomInside('Return');
        $this->prepareQuery();
    }
    //__set, __unset, __clone
}

?>