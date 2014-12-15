<?php

namespace Analyzer\Classes;

use Analyzer;

class UsedPrivateMethod extends Analyzer\Analyzer {

    public function analyze() {
        // property used in a static methodcall \a\b::b()
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'classname')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // property used in a static methodcall static::b() or self
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // property used in a normal methodcall with $this $this->b()
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();
    }
}

?>
