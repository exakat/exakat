<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedParentMP extends Analyzer\Analyzer {
    public function dependsOn() {
        return array();
    }

    public function analyze() {
        // parent::method()
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->code('parent')
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->code('parent')
             ->back('first')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>