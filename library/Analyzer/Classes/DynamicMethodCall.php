<?php

namespace Analyzer\Classes;

use Analyzer;

class DynamicMethodCall extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Methodcall")
             ->outIs('METHOD')
             ->outIs('NAME')
             ->atomIs('Variable')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Staticmethodcall")
             ->outIs('METHOD')
             ->outIs('NAME')
             ->atomIs('Variable')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
