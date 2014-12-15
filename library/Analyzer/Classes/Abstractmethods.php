<?php

namespace Analyzer\Classes;

use Analyzer;

class Abstractmethods extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->atomInside('Function')
             ->hasOut('ABSTRACT');
    }
}

?>
