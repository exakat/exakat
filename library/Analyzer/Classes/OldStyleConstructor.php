<?php

namespace Analyzer\Classes;

use Analyzer;

class OldStyleConstructor extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == "__construct"}.any() == false}')
             ->atomInside('Function')
             ->outIs("NAME")
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
