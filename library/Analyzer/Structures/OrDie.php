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
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_DIE', 'T_EXIT'))
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
