<?php

namespace Analyzer\Common;

use Analyzer;

class FunctionDefinition extends Analyzer\Analyzer {
    protected $functions = array();
    
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition',
                     'Analyzer\\Interfaces\\InterfaceMethod',
                     'Analyzer\\Traits\\TraitMethod');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIsNot('Analyzer\\Classes\\MethodDefinition')
             ->analyzerIsNot('Analyzer\\Interfaces\\InterfaceMethod')
             ->analyzerIsNot('Analyzer\\Traits\\TraitMethod')

             ->code($this->functions, false);
    }
}

?>