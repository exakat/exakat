<?php

namespace Analyzer\Functions;

use Analyzer;

class OneLetterFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\Functionnames');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Functions\\Functionnames')
             ->fullcodeLength(" == 1 ");
        $this->prepareQuery();
    }
}

?>