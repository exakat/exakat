<?php

namespace Analyzer\Classes;

use Analyzer;

class ShouldUseSelf extends Analyzer\Analyzer {

    public function analyze() {
        // full nsname\classname instead of self
        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('parent', 'self'))
             ->savePropertyAs('fullnspath', 'fns')
             ->goToClass()
             ->samePropertyAs('fullnspath', 'fns')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('parent', 'self'))
             ->savePropertyAs('fullnspath', 'fns')
             ->goToClass()
             ->samePropertyAs('fullnspath', 'fns')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('parent', 'self'))
             ->savePropertyAs('fullnspath', 'fns')
             ->goToClass()
             ->samePropertyAs('fullnspath', 'fns')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
