<?php

namespace Analyzer\Classes;

use Analyzer;

class UsedMethods extends Analyzer\Analyzer {
    public function analyze() {
        // Normal Methodcall
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot(array('__construct', '__destruct', '__get', '__set', '__call', '__callstatic'))
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Methodcall"]].out("METHOD").filter{ it.code.toLowerCase() == method.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        // Staticmethodcall
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot(array('__construct', '__destruct', '__get', '__set', '__call', '__callstatic'))
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticmethodcall"]].out("METHOD").filter{ it.code.toLowerCase() == method.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();
        
        // the special methods must be processed independantly
        // __destruct is always used, no need to spot
        
        
    }
}

?>
