<?php

namespace Analyzer\Functions;

use Analyzer;

class WithoutReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->hasNoOut('ABSTRACT')
             ->outIs('NAME')
             ->codeIsNot(array("__construct", '__destruct', '__wakeup'))
             ->back('first')
             ->noAtomInside('Return');
        $this->prepareQuery();
    }
}

?>