<?php

namespace Analyzer\Structures;

use Analyzer;

class CouldBeStatic extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Global')
             ->hasFunction()
             ->outIs('GLOBAL')
             ->savePropertyAs('code', 'theGlobal')
             ->goToFunction()
             ->outIs('NAME')
             ->savePropertyAs('code', 'theFunction')

             // this variable is both in the current function and another
             ->filter('g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("code", theGlobal)
                        .in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}
                           .filter{ it.out("NAME").has("code", theFunction).any() == false}
                        .any() == false')

             // this variable is both in the current function and the global space
             ->filter('g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("code", theGlobal)
                        .in.loop(1){!(it.object.atom in ["Function", "File"])}{it.object.atom == "File"}
                        .any() == false')

             // this variable is both in the current function and another via $GLOBALS
             ->filter('g.idx("atoms")[["atom":"Array"]].filter{ it.out("VARIABLE").has("code", "\$GLOBALS").any()}
                         .out("INDEX").filter{ "\$" + it.noDelimiter == theGlobal}.any() == false')
            // todo : add check on function to avoid itself.
             ->back('first');
        $this->prepareQuery();
        
        // todo : add support for the $GLOBALS 
    }
}

?>
