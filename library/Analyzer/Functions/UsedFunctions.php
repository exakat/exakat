<?php

namespace Analyzer\Functions;

use Analyzer;

class UsedFunctions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}')
             ->raw('filter{it.out("NAME").next().code != ""}')
             ->outIs('NAME')
             ->raw("filter{ g.idx('Functioncall')[['token':'node']].has('fullnspath', it.fullnspath).any() }");
        $this->prepareQuery();
    }
}

?>