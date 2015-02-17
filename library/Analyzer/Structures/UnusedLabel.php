<?php

namespace Analyzer\Structures;

use Analyzer;

class UnusedLabel extends Analyzer\Analyzer {
    public function analyze() {
        // inside functions
        $this->atomIs('Label')
             ->outIs('LABEL')
             ->savePropertyAs('code', 'name')
             ->goToFunction()
             ->raw('filter{ it.out("BLOCK").out.loop(1){ true }{ it.object.atom == "Goto"}.out("LABEL").has("code", name).any() == false }')
             ->back('first');
        $this->prepareQuery();

        // inside namespaces are not processed here.

        // in the global space
        $this->atomIs('Label')
             ->outIs('LABEL')
             ->savePropertyAs('code', 'name')
             ->hasNoFunction()
             ->raw('filter{ g.idx("atoms")[["atom":"Goto"]].out("LABEL").has("code", name).

             // Goto also needs to have no function
             filter{ it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.any() == false}
             .any() == false }')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
