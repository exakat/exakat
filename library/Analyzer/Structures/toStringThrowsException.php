<?php

namespace Analyzer\Structures;

use Analyzer;

class toStringThrowsException extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    public function dependsOn() {
        return array('MethodDefinition');
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