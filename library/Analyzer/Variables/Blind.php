<?php

namespace Analyzer\Variables;

use Analyzer;

class Blind extends Analyzer\Analyzer {
    
    function analyze() {
        $this->atomIs("Variable")
             ->_as('x')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

        $this->atomIs("Variable")
             ->_as('x')
             ->in('VALUE')
             ->atomIs('Keyvalue')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

        $this->atomIs("Variable")
             ->_as('x')
             ->in('KEY')
             ->atomIs('Keyvalue')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

// cases of references
        $this->atomIs("Variable")
             ->_as('x')
             ->in('REFERENCE')
             ->in('VALUE')
             ->atomIs('Keyvalue')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

        $this->atomIs("Variable")
             ->_as('x')
             ->in('REFERENCE')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();
    }
}

?>