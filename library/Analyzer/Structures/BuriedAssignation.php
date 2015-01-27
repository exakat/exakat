<?php

namespace Analyzer\Structures;

use Analyzer;

class BuriedAssignation extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Assignation')
             ->hasNoIn('ELEMENT')
             // in a IF
             ->raw('filter{ it.in("CODE").in("CONDITION").any() == false}')
             // in a chained assignation
             ->raw('filter{ it.in("RIGHT").has("atom", "Assignation").any() == false}')
             // in a chained assignation
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("atom", "Function").any() == false}')
             ;
        $this->prepareQuery();
    }
}

?>
