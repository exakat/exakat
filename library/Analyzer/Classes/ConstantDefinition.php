<?php

namespace Analyzer\Classes;

use Analyzer;

class ConstantDefinition extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Const")
             ->classIsNot('Global')
             ->functionIs('Global')
             ->out('NAME');
        $this->prepareQuery();
    }
}

?>