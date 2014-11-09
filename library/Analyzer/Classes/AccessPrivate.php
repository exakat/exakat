<?php

namespace Analyzer\Classes;

use Analyzer;

class AccessPrivate extends Analyzer\Analyzer {
    public function analyze() {
        // methods  
        // classname::method() direct class
        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.has("fullnspath", inside).any() == false}')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("NAME").next().code == name}.out("PRIVATE").any()}')
             ->back('first');
        $this->prepareQuery();

        // classname::method() parent class through extension (not the first one)
        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.has("fullnspath", inside).any() == false}')
             ->classDefinition()
             ->hasOut('EXTENDS')
             ->raw('filter{ it.out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); } 
                              .loop(2)
                              {true}
                              { it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("NAME").next().code == name}.out("PRIVATE").any()}.any()
                              
                          }')
             ->back('first');
        $this->prepareQuery();

        // parent::method() (immediate parent)
        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->code('parent')
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.has("fullnspath", inside).any() == false}')
             ->classDefinition()
             ->hasOut('EXTENDS')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->inIs('NAME')
             ->outIs('PRIVATE')
             ->back('first');
        $this->prepareQuery();

        // parent::method() parent class through extension (not the first one)
        $this->atomIs("Staticmethodcall")
             ->raw('sideEffect{ first = it; }')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->code('parent')
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.has("fullnspath", inside).any() == false}')
             ->classDefinition()
             ->hasOut('EXTENDS')
             ->raw('filter{ it.out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); } 
                              .loop(2)
                              {true}
                              { it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("NAME").next().code == name}.out("PRIVATE").any()}.any()
                              
                          }')
             ->raw('transform{ first; }');
        $this->prepareQuery();

        // self / static::method() in parent class
        // static : the class which is called 
        // self   : the class where the definition is 
        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->raw('filter{ inside = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("NAME").next().code == name}.out("PRIVATE").any() == false}')
             ->classDefinition()
             ->hasOut('EXTENDS')
             ->raw('filter{ it.out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); } 
                              .loop(2)
                              {true}
                              { it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{it.out("NAME").next().code == name}.out("PRIVATE").any()}.any()
                              
                          }')
             ->back('first');
        $this->prepareQuery();

        // properties 
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
        
        // parent::$property
        $this->atomIs("Staticproperty")
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->code('parent')
             ->goToClass()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).in("DEFINE").out("PRIVATE").any()}')
             ->back('first');
        $this->prepareQuery();
        return false;
    }
}

?>