<?php

namespace Analyzer\Functions;

use Analyzer;

class HasNotFluentInterface extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs('RETURN')
             ->atomIsNot('Variable')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs('RETURN')
             ->atomIs('Variable')
             ->codeIsNot('$this')
             ->back('first');
        $this->prepareQuery();

        // no return == return null!
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->noAtomInside('Return')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
