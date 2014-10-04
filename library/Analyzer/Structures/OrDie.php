<?php

namespace Analyzer\Structures;

use Analyzer;

class OrDie extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Logical")
             ->code(array('or', '||'))
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>