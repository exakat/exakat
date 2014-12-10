<?php

namespace Analyzer\Functions;

use Analyzer;

class FunctionCalledWithOtherCase extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->inIsNot(array('NEW', 'METHOD'))
             ->savePropertyAs('code', 'name')
             ->functionDefinition()
             ->samePropertyAs('code', 'name')
             ->notSamePropertyAs('code', 'name', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>