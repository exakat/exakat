<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticMethodsCalledFromObject extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition");
    }

    public function analyze() {
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->raw("filter{ x = it; g.V.has('atom', 'Function').filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.filter{ it.out('STATIC').any()}.out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.any() }")
             ->back('first');
    }
}

?>