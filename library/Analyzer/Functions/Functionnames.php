<?php

namespace Analyzer\Functions;

use Analyzer;

class Functionnames extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Function")
             ->classIs('Global')
             ->out('NAME');
    }
}

?>