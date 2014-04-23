<?php

namespace Analyzer\Functions;

use Analyzer;

class FunctionCalledWithOtherCase extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition");
    }

    public function analyze() {
        $this->atomIs("Functioncall")
             ->inIsnot('NEW')
             ->raw("filter{ x = it; g.V.has('atom', 'Function').filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').count() == 0}.out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.hasNot('code', it.code).any() }");
        $this->prepareQuery();

        $this->atomIs("Methodcall")
             ->outIs('METHOD')
             ->raw("filter{ x = it; g.V.has('atom', 'Function').filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.hasNot('code', it.code).any() }");
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->raw("filter{ x = it; g.V.has('atom', 'Function').filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.out('NAME').filter{it.code.toLowerCase() == x.code.toLowerCase()}.hasNot('code', it.code).any() }");
        $this->prepareQuery();
    }

}

?>