<?php

namespace Analyzer\Namespaces;

use Analyzer;

class Alias extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Nsname")
             ->outIs('AS');
    }
}

?>