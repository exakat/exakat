<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UsedUse extends Analyzer\Analyzer {

//////////////////////////////////////////////////////////////////////////////////////////
// case of use without alias nor namespacing (use A), single or multiple declaration
//////////////////////////////////////////////////////////////////////////////////////////
    public function analyze() {
    // case of simple subuse in a new with alias :  use a\b; new b\c()
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('New')
             ->outIs('NEW')
             ->outIs('SUBNAME')
             ->is('rank', 0)
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a new with alias :  use a; new a()
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->atomIs('Identifier')
             ->savePropertyAs('alias', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->atomInside('New')
             ->outIs('NEW')
             ->tokenIs('T_STRING')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in Typehint
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Typehint')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of alias use in extends or implements
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Class')
             ->outIs('EXTENDS', 'IMPLEMENTS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a extends
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Class')
             ->outIs('EXTENDS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a implements
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Class')
             ->outIs('IMPLEMENTS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();
        
    // case of simple use in a Static constant
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static property
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static method
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('code', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a instanceof
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->savePropertyAs('fullnspath', 'used')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Instanceof')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'used')
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
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Typehint')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a new
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('New')
             ->outIs('NEW')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a extends
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Class')
             ->outIs('EXTENDS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a implements
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Class')
             ->outIs('IMPLEMENTS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();
        
    // case of simple use in a Static constant
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticconstant')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static property
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of simple use in a Static method
        $this->atomIs("Use")
             ->outIs('USE')
             ->_as('result')
             ->outIs(array('AS', 'SUBNAME'))
             ->savePropertyAs('code', 'used')
             ->inIs(array('AS', 'SUBNAME'))
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('code', 'used')
             ->back('result');
        $this->prepareQuery();

    // case of alias use in a instanceof
        // subcase for the original path
        $this->atomIs("Use")
             ->outIs('USE')
             ->analyzerIsNot('Analyzer\\Namespaces\\UsedUse')
             ->_as('result')
             ->raw('sideEffect{ thealias = it;}')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Instanceof')
             ->outIs('CLASS')
             ->raw('filter{ it.fullcode.toLowerCase() == thealias.originpath.toLowerCase()}')
             ->raw('transform{ thealias}');
        $this->prepareQuery();

        // subcase for the alias
        $this->atomIs("Use")
             ->outIs('USE')
             ->analyzerIsNot('Analyzer\\Namespaces\\UsedUse')
             ->_as('result')
             ->raw('sideEffect{ result = it;}')
             ->savePropertyAs('alias', 'thealias')
             ->inIs('USE')
             ->inIs('ELEMENT')
             ->inIs(array('CODE', 'BLOCK'))
             ->atomInside('Instanceof')
             ->outIs('CLASS')
             ->samePropertyAs('fullcode', 'thealias')
             ->raw('transform{ result}');
        $this->prepareQuery();

    }
}

?>