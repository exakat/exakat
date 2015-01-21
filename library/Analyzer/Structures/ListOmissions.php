<?php

namespace Analyzer\Structures;

use Analyzer;

class ListOmissions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\VariableUsedOnceByContext');
    }
    
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_LIST', 'T_NS_SEPARATOR'))
             ->fullnspath('\\list')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->analyzerIs('Analyzer\\Variables\\VariableUsedOnceByContext');
        $this->prepareQuery();
    }
}

?>
