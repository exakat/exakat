<?php

namespace Analyzer\Structures;

use Analyzer;

class UncheckedResources extends Analyzer\Analyzer {
    public function analyze() {
        //readdir(opendir('uncheckedDir4'));
        $this->atomFunctionIs('opendir')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->fullnspath(array('\\readdir', '\\rewinddir', '\\closedir'));
        $this->prepareQuery();

        //$dir = opendir('uncheckedDir4'); readdir($dir);
        $this->atomFunctionIs('opendir')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->raw('filter{ it.in("CODE").in("CONDITION").any() == false }')
             ->_as('result')
             ->outIs('LEFT')
//             ->savePropertyAs('code', 'resource')
//             ->inIs('LEFT')
             ->nextVariable('resource')
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("fullnspath", "\\\\is_resource").any() == false }')
             ->raw('filter{ it.in("NOT").any() == false }')
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").in("RIGHT").in("CODE").in("RIGHT").has("atom", "Comparison").in("CONDITION").any() == false }')
             ->back('result');
//        $this->printQuery();
        $this->prepareQuery();
    }
}

?>
