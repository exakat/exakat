<?php

namespace Analyzer\Classes;

use Analyzer;

class DynamicMethodCall extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Methodcall")
             ->outIs('METHOD')
             ->atomIs('Variable')
             ->back('first');
        $this->prepareQuery();
    }
}

?>