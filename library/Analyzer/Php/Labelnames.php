<?php

namespace Analyzer\Php;

use Analyzer;

class Labelnames extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Label")
             ->out('LABEL');
    }
}

?>