<?php

namespace Analyzer\Classes;

use Analyzer;

class UnusedPrivateMethod extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\UsedPrivateMethod');
    }

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->_as('block')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasOut('PRIVATE')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod');
        $this->prepareQuery();
    }
}

?>
