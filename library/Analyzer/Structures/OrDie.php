<?php

namespace Analyzer\Structures;

use Analyzer;

class OrDie extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Structures\\NoDirectAccess');
    }
    
    public function analyze() {
        $this->atomIs('Logical')
             ->analyzerIsNot('Analyzer\\Structures\\NoDirectAccess')
             ->code(array('or', '||'))
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>