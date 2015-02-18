<?php

namespace Analyzer\Php;

use Analyzer;

class MethodCallOnNew extends Analyzer\Analyzer {
    public $phpversion = '5.4+';
    
    public function analyze() {
        $this->atomIs('Parenthesis')
             ->outIs('CODE')
             ->atomIs('New')
             ->inIs('CODE')
             ->inIs('OBJECT')
             ->atomIs(array('Property', 'Methodcall'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
