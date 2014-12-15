<?php

namespace Analyzer\Arrays;

use Analyzer;

class NonConstantArray extends Analyzer\Analyzer {

    public function dependsOn() {
        return array('Analyzer\\Constants\\Constantnames');
    }
    
    public function analyze() {
        $this->atomIs("Array")
             ->outIs('INDEX')
             ->atomIs('Identifier')
             ->analyzerIsNot('Analyzer\\Constants\\Constantnames')
             ->hasNoConstantDefinition();
        $this->prepareQuery();

        $this->atomIs("Array")
             ->isNot('in_quote', "'true'")
             ->isNot('enclosing', null)
             ->outIs('INDEX')
             ->atomIs('Identifier')
             ->analyzerIsNot('Analyzer\\Constants\\Constantnames')
             ->hasNoConstantDefinition();
        $this->prepareQuery();
    }
}

?>
