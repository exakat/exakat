<?php

namespace Analyzer\Classes;

use Analyzer;

class Classnames extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Class")
             ->outIs('NAME');
    }
}

?>