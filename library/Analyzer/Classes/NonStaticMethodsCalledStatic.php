<?php

namespace Analyzer\Classes;

use Analyzer;

class NonStaticMethodsCalledStatic extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition",
                     "Analyzer\\Classes\\StaticMethods",
        );
    }

    public function analyze() {
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->raw("filter{ x = it;  g.V.has('atom', 'Function').out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\StaticMethods').count() == 0}.any() }")
             ->back('first');
    }
}

?>