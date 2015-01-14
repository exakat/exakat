<?php

namespace Analyzer\Functions;

use Analyzer;

class WithoutReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->hasNoOut('ABSTRACT')
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Interface").any() == false}')
             ->outIs('NAME')
             ->codeIsNot(array('__construct', '__destruct', '__wakeup', '__autoload'))
             ->back('first')
             ->noAtomInside('Return');
        $this->prepareQuery();
    }
}

?>
