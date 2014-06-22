<?php

namespace Analyzer\Classes;

use Analyzer;

class UnusedPrivateProperty extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\UsedPrivateProperty');
    }

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->_as('block')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasOut('PRIVATE')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateProperty');
        $this->prepareQuery();
    
    }
}

?>