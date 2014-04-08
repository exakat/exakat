<?php

namespace Analyzer\Php;

use Analyzer;

class ThrowUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Throw");
    }
}

?>