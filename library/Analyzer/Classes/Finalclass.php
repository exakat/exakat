<?php

namespace Analyzer\Classes;

use Analyzer;

class Finalclass extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('FINAL')
             ->back('first');
    }
}

?>