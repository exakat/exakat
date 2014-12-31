<?php

namespace Analyzer\Classes;

use Analyzer;

class ConstantClass extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->raw('filter{ it.out("ELEMENT").count() > 0}')
             ->raw('filter{ it.out("ELEMENT").filter{ it.atom != "Const" }.any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Interface")
             ->outIs('BLOCK')
             ->raw('filter{ it.out("ELEMENT").count() > 0}')
             ->raw('filter{ it.out("ELEMENT").filter{ it.atom != "Const" }.any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
