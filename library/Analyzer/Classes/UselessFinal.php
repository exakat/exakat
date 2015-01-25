<?php

namespace Analyzer\Classes;

use Analyzer;

class UselessFinal extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->hasOut('FINAL')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasOut('FINAL');
        $this->prepareQuery();
    }
}

?>
