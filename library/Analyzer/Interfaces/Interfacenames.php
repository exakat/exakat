<?php

namespace Analyzer\Interfaces;

use Analyzer;

class Interfacenames extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Interface")
             ->outIs('NAME');
    }
}

?>
