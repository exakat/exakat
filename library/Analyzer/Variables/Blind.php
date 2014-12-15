<?php

namespace Analyzer\Variables;

use Analyzer;

class Blind extends Analyzer\Analyzer {
    
    public function analyze() {
        $blinds = array("Variable", 'Staticproperty', 'Property', 'Array');
        
// foreach($source as $blind)
        $this->atomIs($blinds)
             ->_as('x')
             ->atomIsNot('Keyvalue')
             ->inIs('VALUE')
             ->atomIs('Foreach')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('x');
        $this->prepareQuery();

// foreach($source as $blindKey => $blindValue)
        $this->atomIs($blinds)
             ->_as('x')
             ->inIs('VALUE')
             ->atomIs('Keyvalue')
             ->inIs('VALUE')
             ->atomIs('Foreach')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('x');
        $this->prepareQuery();

        $this->atomIs($blinds)
             ->_as('x')
             ->inIs('KEY')
             ->atomIs('Keyvalue')
             ->inIs('VALUE')
             ->atomIs('Foreach')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('x');
        $this->prepareQuery();

// cases of references
// foreach($source as &$blind)
        $this->atomIs($blinds)
             ->_as('x')
             ->inIs('REFERENCE')
             ->inIs('VALUE')
             ->atomIs('Foreach')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('x');
        $this->prepareQuery();

// foreach($source as $blindKey => &$blindValue)
        $this->atomIs($blinds)
             ->_as('x')
             ->inIs('REFERENCE')
             ->inIs('VALUE')
             ->atomIs('Keyvalue')
             ->inIs('VALUE')
             ->atomIs('Foreach')
             ->outIs('BLOCK')
             ->setApplyBelow(true)
             ->back('x');
        $this->prepareQuery();
        
// Keys can't be references
    }
}

?>
