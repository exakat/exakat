<?php

namespace Analyzer\Classes;

use Analyzer;

class LocallyUnusedProperty extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Classes\\LocallyUsedProperty");
    }
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->analyzerIsNot('Analyzer\\Classes\\LocallyUsedProperty');
        $this->prepareQuery();
    }
}

?>
