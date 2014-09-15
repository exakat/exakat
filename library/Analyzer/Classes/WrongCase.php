<?php

namespace Analyzer\Classes;

use Analyzer;

class WrongCase extends Analyzer\Analyzer {

    public function analyze() {
// New
        $this->atomIs("New")
             ->outIs('NEW')
             ->tokenIs('T_STRING')
             ->isNot('aliased', 'true')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("New")
             ->outIs('NEW')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// staticMethodcall
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', 'true')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Staticproperty
        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', 'true')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Staticconstant
        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->isNot('aliased', 'true')
             ->tokenIs('T_STRING')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Catch
        $this->atomIs("Catch")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', 'true')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Catch")
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// Typehint
        $this->atomIs("Typehint")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', 'true')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        $this->atomIs("Typehint")
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

// instance of
        $this->atomIs("Instanceof")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->isNot('aliased', 'true')
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Instanceof")
             ->outIs('CLASS')
             ->tokenIs('T_NS_SEPARATOR')
             ->orderIs('SUBNAME', 'last')
             ->savePropertyAs('code', 'classe')
             ->inIs('SUBNAME')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// use 
        $this->atomIs("Use")
             ->outIs('USE')
             ->savePropertyAs('originclass', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased instanceof 
        $this->atomIs("Instanceof")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', 'true')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->notSamePropertyAs('alias', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased static constant call 
        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', 'true')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->notSamePropertyAs('alias', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased static property
        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', 'true')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->notSamePropertyAs('alias', 'classe', true)
             ->back('first');
        $this->prepareQuery();

// aliased static method
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', 'true')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->notSamePropertyAs('alias', 'classe', true)
             ->back('first');
        $this->prepareQuery();    

// aliased typehint 
        $this->atomIs("Typehint")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', 'true')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->notSamePropertyAs('alias', 'classe', true)
             ->back('first');
        $this->prepareQuery();    

// aliased catch
        $this->atomIs("Catch")
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->is('aliased', 'true')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->notSamePropertyAs('alias', 'classe', true)
             ->back('first');
        $this->prepareQuery();    
    }
}

?>