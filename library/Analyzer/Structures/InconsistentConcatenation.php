<?php

namespace Analyzer\Structures;

use Analyzer;

class InconsistentConcatenation extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Concatenation')
             ->filter('it.out("CONCAT").filter{it.atom in ["Variable", "Array", "Functioncall", "Property", "Methodcall", "Staticmethodcall", "Staticconsant", "Staticproperty"]}.any()')
             ->outIs('CONCAT')
             ->atomIs('String')
             ->outIs('CONTAINS')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
