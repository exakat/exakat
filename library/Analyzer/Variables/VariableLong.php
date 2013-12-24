<?php

namespace Analyzer\Variables;

use Analyzer;

class VariableLong extends Analyzer\Analyzer {
    function analyze() {
        $this->atomIs("Variable")
             ->hasNoIn('DEFINE')
             ->hasNoIn('PROPERTY') // avoid static properties
             ->codeLength(" > 20 ");
    }
}

?>