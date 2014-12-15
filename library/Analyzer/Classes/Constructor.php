<?php

namespace Analyzer\Classes;

use Analyzer;

class Constructor extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('constructor')
             ->outIs('NAME')
             ->code('__construct')
             ->back('constructor');
        $this->prepareQuery();

        $this->atomIs("Class")
             ->outIs('NAME')
             ->savePropertyAs('code', 'code')
             ->back('first')
             ->outIs('BLOCK')
             ->raw('filter{ it.out("ELEMENT").has("atom", "Function").out("NAME").has("code", "__construct").any() == false }')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('constructor')
             ->outIs('NAME')
             ->samePropertyAs('code', 'code')
             ->back('constructor')
             ;
        $this->prepareQuery();
    }
}

?>
