<?php

namespace Analyzer\Structures;

use Analyzer;

class DanglingArrayReferences extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Foreach")
             ->outIs('VALUE')
             ->is('reference', 'true')
             ->savePropertyAs('code', 'array')
             ->back('first')
             ->nextSibling()
             ->raw('filter{ it.has("atom", "Functioncall").has("code", "unset").out("ARGUMENTS").out("ARGUMENT").filter{ it.code == array }.any() == false; }')
             ->back('first')
             ;
        $this->prepareQuery();
    }
}

?>
