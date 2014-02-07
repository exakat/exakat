<?php

namespace Analyzer\Variables;

use Analyzer;

class References extends Analyzer\Analyzer {
    function analyze() {
        $this->atomIs("Variable")
             ->is('reference');
    }
}

?>