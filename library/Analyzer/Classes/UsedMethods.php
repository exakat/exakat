<?php

namespace Analyzer\Classes;

use Analyzer;

class UsedMethods extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\MarkCallable');
    }
    
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini');
        $magicMethods = $magicMethods['magicMethod'];

        // Normal Methodcall
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Methodcall"]].out("METHOD").filter{ it.code.toLowerCase() == method.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
            // call with call_user_func
             ->raw('filter{ g.idx("atoms")[["atom":"Functioncall"]].hasNot("fullnspath", null).has("fullnspath", "\\\\call_user_func").any() }')
             ->back('used');
        $this->prepareQuery();
        
        // Staticmethodcall
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticmethodcall"]].out("METHOD").filter{ it.code.toLowerCase() == method.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        // the special methods must be processed independantly
        // __destruct is always used, no need to spot

        // method used statically in a callback with an array
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Functioncall"]].has("token", "T_ARRAY").hasNot("cbClass", null).filter{ it.cbMethod == method.toLowerCase()}.filter{ it.cbClass == fullnspath.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"String"]].hasNot("cbClass", null).filter{ it.cbMethod == method.toLowerCase()}.filter{ it.cbClass == fullnspath.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();
        
    }
}

?>
