<?php

namespace Analyzer\Classes;

use Analyzer;

class PropertyNeverUsed extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\PropertyUsedInternally");
    }

    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('Analyzer\\Classes\\PropertyUsedInternally');
        $this->prepareQuery();
    }
}

?>
