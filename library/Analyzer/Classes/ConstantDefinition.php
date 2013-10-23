<?php

namespace Analyzer\Classes;

use Analyzer;

class ConstantDefinition extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Const")
             ->out('NAME');
        $this->prepareQuery();
    }
}

?>