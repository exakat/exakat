<?php

namespace Analyzer\Functions;

use Analyzer;

class WithoutReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->isNot('code', "'__construct'")
             ->back('first')
             ->noAtomInside('Return')
             ;
    }
}

?>