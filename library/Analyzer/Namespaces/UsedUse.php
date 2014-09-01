<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UsedUse extends Analyzer\Analyzer {

//////////////////////////////////////////////////////////////////////////////////////////
// case of use without alias nor namespacing (use A), single or multiple declaration
//////////////////////////////////////////////////////////////////////////////////////////
    public function analyze() {
    // case of simple use in a new with alias
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('New')
             ->outIs('NEW')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in Typehint
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Typehint')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a extends
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Class')
             ->outIs('EXTENDS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a implements
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Class')
             ->outIs('IMPLEMENTS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static constant
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static property
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static method
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a instanceof
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('fullnspath', 'use')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Instanceof')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'use')
             ->back('result');
        $this->prepareQuery();

//////////////////////////////////////////////////////////////////////////////////////////
// case of use with alias (use A as B), single or multiple declaration
//////////////////////////////////////////////////////////////////////////////////////////
    // case of simple use in Typehint
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Typehint')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a new
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('New')
             ->outIs('NEW')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a extends
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Class')
             ->outIs('EXTENDS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a implements
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Class')
             ->outIs('IMPLEMENTS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static constant
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static property
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static method
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'use')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'use')
             ->back('result');
        $this->prepareQuery();

    // case of alias use in a instanceof
    // @bug : can't use _as because of a null error! Only available is 'first'
        $this->atomIs("Use")
             ->outIs('USE')
             ->analyzerIsNot('Analyzer\\Namespaces\\UsedUse')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'thealias')
             ->back('first')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('Instanceof')
             ->outIs('CLASS')
             ->atomIs('Identifier')
             ->samePropertyAs('code', 'thealias')
             ->back('first')
             ->outIs('USE')
             ->outIs(array('AS', 'SUBNAME'))
             ->samePropertyAs('code', 'thealias')
             ->inIs(array('AS', 'SUBNAME'))
             ;
        $this->prepareQuery();
    }
}

?>