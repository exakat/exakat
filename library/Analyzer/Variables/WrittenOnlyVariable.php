<?php

namespace Analyzer\Variables;

use Analyzer;

class WrittenOnlyVariable extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified',
                     'Analyzer\\Variables\\IsRead');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->analyzerIs('Analyzer\\Variables\\IsModified')
             ->analyzerIsNot('Analyzer\\Variables\\IsRead')
             ->raw('filter{ 
    name = it.code; 
    itself = it; 
    it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.out("BLOCK").
             out().loop(1){true}{it.object.atom == "Variable"}
             .has("code", name)
             .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsRead").any()}
             .any() == false
             }')
             ;
        $this->prepareQuery();
    }
}

?>
