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
             ->raw("filter{ x = it;  g.V.has('atom', 'Function').out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\StaticMethods').any()}.any() }")
             ->back('first');
    }
}

?>