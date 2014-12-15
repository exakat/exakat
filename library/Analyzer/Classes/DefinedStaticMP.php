<?php

namespace Analyzer\Classes;

use Analyzer;

class DefinedStaticMP extends Analyzer\Analyzer {
    public function analyze() {
        // static::method() 1rst level
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::method() 2nd level
        $this->atomIs("Staticmethodcall")
             ->analyzerIsNot('Analyzer\\\\Classes\\\\DefinedStaticMP')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::method() 3rd level
        $this->atomIs("Staticmethodcall")
             ->analyzerIsNot('Analyzer\\\\Classes\\\\DefinedStaticMP')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::$property 1rst level
        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::$property 2nd level
        $this->atomIs("Staticproperty")
             ->analyzerIsNot('Analyzer\\\\Classes\\\\DefinedStaticMP')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::$property 3rd level
        $this->atomIs("Staticproperty")
             ->analyzerIsNot('Analyzer\\\\Classes\\\\DefinedStaticMP')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->raw('in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
