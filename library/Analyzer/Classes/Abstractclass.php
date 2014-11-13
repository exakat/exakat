<?php

namespace Analyzer\Classes;

use Analyzer;

class Abstractclass extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('ABSTRACT')
             ->back('first');
        $this->prepareQuery();
    }
}

?>