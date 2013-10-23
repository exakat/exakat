<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyDefinition extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Ppp")
             ->out('DEFINE')
             ->atomIs('Variable');
             
    }
}

?>