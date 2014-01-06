<?php

namespace Analyzer\Variables;

use Analyzer;

class Blind extends Analyzer\Analyzer {
    
    function analyze() {
        $this->setApplyBelow(true);
        
// foreach($source as $blind)
        $this->atomIs("Variable")
             ->_as('x')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

// foreach($source as $blindKey => $blindValue)
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
// foreach($source as &$blind)
        $this->atomIs("Variable")
             ->_as('x')
             ->in('REFERENCE')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

// foreach($source as $blindKey => &$blindValue)
        $this->atomIs("Variable")
             ->_as('x')
             ->in('REFERENCE')
             ->in('VALUE')
             ->atomIs('Keyvalue')
             ->in('VALUE')
             ->atomIs('Foreach')
             ->back('x');
        $this->prepareQuery();

// Keys can't be references
    }
}

?>