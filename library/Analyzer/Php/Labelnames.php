<?php

namespace Analyzer\Php;

use Analyzer;

class Labelnames extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Label")
             ->outIs('LABEL');
    }
}

?>