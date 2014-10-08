<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UseFunctionsConstants extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Use")
             ->outIs(array('CONST','FUNCTION'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>