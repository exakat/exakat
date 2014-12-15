<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticCpm extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs("BLOCK")
             ->atomInside('Function')
             ->hasOut('STATIC')
             ->outIs('NAME');
        $this->prepareQuery();

        $this->atomIs("Class")
             ->outIs("BLOCK")
             ->atomInside('Ppp')
             ->hasOut('STATIC')
             ->outIs('DEFINE');
        $this->prepareQuery();
    }
}

?>
