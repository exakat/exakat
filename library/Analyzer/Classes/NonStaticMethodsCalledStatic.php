<?php

namespace Analyzer\Classes;

use Analyzer;

class NonStaticMethodsCalledStatic extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition");
    }

    public function analyze() {
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->raw("filter{ x = it; g.V.has('atom', 'Function').filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.filter{ it.out('STATIC').count() == 0}.out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.any() }")
             ->back('first');
    }
}

?>