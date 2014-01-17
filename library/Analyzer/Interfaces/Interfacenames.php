<?php

namespace Analyzer\Interfaces;

use Analyzer;

class Interfacenames extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Interface")
             ->out('NAME');
    }
}

?>