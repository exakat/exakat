<?php

namespace Analyzer\Classes;

use Analyzer;

class NonStaticMethodsCalledStatic extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition",
                     "Analyzer\\Classes\\StaticMethods"
        );
    }

    public function analyze() {
        // check outside the class
        $this->atomIs('Staticmethodcall')
             ->notInClass()
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self', 'static'))
             ->inIs('CLASS')
             ->raw("filter{ x = it;

                        g.idx('atoms')[['atom':'Function']]
                           .filter{ it.out('NAME').next().code.toLowerCase() == x.out('METHOD').next().code.toLowerCase()}.
                            filter{ it.in('ELEMENT').in('BLOCK').out('NAME').next().code.toLowerCase() == x.out('CLASS').next().code.toLowerCase()}.
                            filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.
                            filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\StaticMethods').count() == 0}
                           .any() }");
        $this->prepareQuery();

        // check inside the class
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self', 'static'))

             ->isNotGrandParent()
             ->savePropertyAs('fullnspath', 'fns')
             ->goToClass()
             ->notSamePropertyAs('fullnspath', 'fns')
// back to initial
             ->back('first');
        $this->prepareQuery();
    }
}

?>
