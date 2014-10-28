<?php

namespace Analyzer\Functions;

use Analyzer;

class HasFluentInterface extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\HasNotFluentInterface',
                     'Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->analyzerIsNot('Analyzer\\Functions\\HasNotFluentInterface')
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->back('first');
        $this->prepareQuery();
    }
}

?>