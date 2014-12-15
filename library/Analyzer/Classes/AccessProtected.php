<?php

namespace Analyzer\Classes;

use Analyzer;

class AccessProtected extends Analyzer\Analyzer {
    public function analyze() {
        // class::method()
        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.has("fullnspath", inside).any() == false}')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).in("NAME").out("PRIVATE").any()}')
             ->back('first');
        $this->prepareQuery();

        // parent::$property
        // static or self ::$property

        // class::$property
        $this->atomIs("Staticproperty")
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.has("fullnspath", inside).any() == false}')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).in("DEFINE").out("PRIVATE").any()}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
