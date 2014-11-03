<?php

namespace Analyzer\Classes;

use Analyzer;

class UnusedMethods extends Analyzer\Analyzer {
    public function analyze() {
        // pss::$property
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('unused')
             ->outIs('NAME')
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Methodcall"]].out("METHOD").filter{ it.code == method}.any() == false}')
             ->back('unused');
        $this->prepareQuery();
    }
}

?>