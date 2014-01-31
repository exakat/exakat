<?php

namespace Analyzer\Php;

use Analyzer;

class Incompilable extends Analyzer\Analyzer {

    function analyze() {
        $this->tokenIs("E_FILE")
             ->is('compile', "'false'");
    }
}

?>