<?php

namespace Analyzer\Traits;

use Analyzer;

class Traitnames extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Trait")
             ->outIs('NAME');
    }
}

?>