<?php

namespace Analyzer\Classes;

use Analyzer;

class OldStyleConstructor extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->atomInside('Function')
             ->outIs("NAME")
             ->samePropertyAs('code', 'name')
             ;
    }
}

?>