<?php

namespace Analyzer\Classes;

use Analyzer;

class ConstantDefinition extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Const")
             ->classIsNot('Global')
             ->functionIs('Global')
             ->outIs('NAME');
        $this->prepareQuery();
    }
}

?>