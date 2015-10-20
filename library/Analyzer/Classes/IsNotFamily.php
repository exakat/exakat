<?php

namespace Analyzer\Classes;

use Analyzer;

class IsNotFamily extends Analyzer\Analyzer {
    public function analyze() {
        // Staticmethodcall
        // Inside the class
        $this->atomIs('Class')
             ->savePropertyAs('classTree', 'classtree')
             ->outIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIsNot(array('self', 'parent', 'static'))
             ->isPropertyNotIn('fullnspath','classtree');
        $this->prepareQuery();

        // All non-in-class calls are OK
        $this->atomIs('Staticmethodcall')
             ->hasNoClass()
             ->outIs('CLASS')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
