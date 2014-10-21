<?php

namespace Analyzer\Classes;

use Analyzer;

class UsedPrivateProperty extends Analyzer\Analyzer {

    public function analyze() {
        // property used in a staticmethodcall \a\b::$b
        $this->atomIs("Ppp")
             ->hasOut('PRIVATE')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'property')
             ->back('first')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->savePropertyAs('fullnspath', 'classe')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticproperty"]].filter{it.out("CLASS").has("fullnspath", classe).any()}.filter{it.out("PROPERTY").has("code", property).any()}.any()}')
             ->back('first');
        $this->prepareQuery();

        // property used in a static property static::$b or self::$b
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateProperty')
             ->_as('ppp')
             ->hasOut('PRIVATE')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'x')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a static property static::$b[] or self::$b[]
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateProperty')
             ->_as('ppp')
             ->hasOut('PRIVATE')
             ->outIs('DEFINE')
             ->savePropertyAs('code', 'x')
             ->inIs('DEFINE')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal methodcall with $this $this->b()
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateProperty')
             ->_as('ppp')
             ->hasOut('PRIVATE')
             ->savePropertyAs('propertyname', 'x')
             ->inIs('ELEMENT')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();

    }
}
?>