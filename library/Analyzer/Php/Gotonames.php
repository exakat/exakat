<?php

namespace Analyzer\Php;

use Analyzer;

class Gotonames extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Goto")
             ->outIs('LABEL');
    }
}

?>