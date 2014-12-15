<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyDefinition extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Ppp")
             ->outIs('DEFINE')
             ->atomIs('Variable');
    }
}

?>
