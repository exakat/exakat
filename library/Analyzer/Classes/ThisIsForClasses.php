<?php

namespace Analyzer\Classes;

use Analyzer;

class ThisIsForClasses extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Variable")
             ->code('$this')
             ->classIs('Global')
             ->traitIs('Global')
             ->back('first');
        $this->prepareQuery();
    }
}

?>