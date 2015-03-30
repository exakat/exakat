<?php

namespace Analyzer\Spip;

use Analyzer;

class UnusedDist extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('NAME')
             ->regex('code', '_dist\\$')
             ->savePropertyAs('code', 'name')
             ->raw(<<<GREMLIN
filter{ g.idx("atoms")[["atom":"Functioncall"]].has("fullnspath", "\\\\charger_fonction")
                                               .sideEffect{ 
                                                    fonction = it.out("ARGUMENTS").out("ARGUMENT").has('token', 'T_CONSTANT_ENCAPSED_STRING').has("rank", 0).next().noDelimiter.replace("/", "_").toLowerCase(); 
                                                    sub = "exec";
                                                    if (it.out("ARGUMENTS").out("ARGUMENT").has('token', 'T_CONSTANT_ENCAPSED_STRING').has("rank", 1).any()) {
                                                        sub = it.out("ARGUMENTS").out("ARGUMENT").has('token', 'T_CONSTANT_ENCAPSED_STRING').has("rank", 1).next().noDelimiter.toLowerCase();
                                                    }
                                                }
                                               .filter{ name.toLowerCase() == sub + "_" + fonction + "_dist" }
                                               .any() == false
}

GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
