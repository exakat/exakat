<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedProperty extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\DefinedProperty',
                     'Analyzer\\Classes\\HasMagicProperty');
    }
    
    public function analyze() {
        // only for internal calls. External calls still needs some work
        $this->atomIs('Property')
             ->outIs('PROPERTY')
             ->tokenIs('T_STRING')
             ->inIs('PROPERTY')
             ->analyzerIsNot('Analyzer\\Classes\\DefinedProperty')
             ->outIs('OBJECT')
             ->code('$this')
             ->goToClass()
             ->analyzerIsNot('Analyzer\\Classes\\HasMagicProperty')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
