<?php

namespace Analyzer\Classes;

use Analyzer;

class WrongCase extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Catch")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Typehint")
             ->outIs('CLASS')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        $this->atomIs("Instanceof")
             ->outIs('RIGHT')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('static', 'parent', 'self'))
             ->savePropertyAs('code', 'classe')
             ->classDefinition()
             ->outIs('NAME')
             ->notSamePropertyAs('code', 'classe', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>