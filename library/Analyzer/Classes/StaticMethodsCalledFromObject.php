<?php

namespace Analyzer\Classes;

use Analyzer;

class StaticMethodsCalledFromObject extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition",
                     "Analyzer\\Classes\\StaticMethods");
    }

    public function analyze() {
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->raw("filter{ x = it;  g.idx('atoms')[['atom':'Function']].
                                         out('NAME')
                                         .filter{it.code.toLowerCase() == x.code.toLowerCase()}
                                         .filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}
                                         .filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\StaticMethods').any()}
                                         .any() }")
             ->back('first');
        $this->prepareQuery();
    }
}

?>
