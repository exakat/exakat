<?php

namespace Analyzer\Structures;

use Analyzer;

class BuriedAssignation extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Assignation')
             ->hasNoIn('ELEMENT')
             // in a While
             ->raw('filter{ it.in("CONDITION", "INIT").any() == false}')

             // in a IF
             ->raw('filter{ it.in("CODE").in("CONDITION").any() == false}')
             // in a if( ($a =2) !== 3) {}
             ->raw('filter{ it.in("CODE").in.has("atom", "Comparison").in("CODE").in("CONDITION").any() == false}')

             // in a chained assignation
             ->raw('filter{ it.in("RIGHT").has("atom", "Assignation").any() == false}')

             // in an argument (with or without typehint)
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("atom", "Function").any() == false}')
             ->raw('filter{ it.in("VARIABLE").in("ARGUMENT").in("ARGUMENTS").has("atom", "Function").any() == false}');
        $this->prepareQuery();
    }
}

?>
