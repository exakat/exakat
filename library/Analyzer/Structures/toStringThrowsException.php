<?php

namespace Analyzer\Structures;

use Analyzer;

class toStringThrowsException extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->code('__toString', true)
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->atomInside('Throw')
             ->back('first');
    }
}

?>