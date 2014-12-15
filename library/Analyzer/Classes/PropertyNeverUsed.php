<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyNeverUsed extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\PropertyUsedInternally");
    }

    public function analyze() {
        // only private classes ATM
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasOut('PRIVATE')
             ->analyzerIsNot('Analyzer\\Classes\\PropertyUsedInternally');
        $this->prepareQuery();
    }
}

?>
