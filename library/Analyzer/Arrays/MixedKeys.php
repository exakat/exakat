<?php

namespace Analyzer\Arrays;

use Analyzer;

class MixedKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Functioncall")
             ->hasIn('VALUE')
             ->tokenIs('T_ARRAY')
             ->fullnspath('\\array')
             ->outIs('ARGUMENTS')
             ->raw('filter{ m=[:]; 
                            it.out("ARGUMENT").groupBy(m){
              if (it.out("KEY").any() && it.out("KEY").next().atom in ["Identifier", "Staticconstant"]) { "a" } else { "b" }
             }{it}{it.size()}.iterate();
m.size() > 1; }')
;
        $this->prepareQuery();
    }
}

?>