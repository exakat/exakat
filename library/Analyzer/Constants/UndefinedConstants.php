<?php

namespace Analyzer\Constants;

use Analyzer;

class UndefinedConstants extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\CustomConstantUsage');
    }
    
    public function analyze() {
        $this->atomIs("Identifier")
             ->analyzerIs('Analyzer\\Constants\\CustomConstantUsage')
             ->hasNoConstantDefinition();
        $this->prepareQuery();
        
        $this->atomIs("Nsname")
             ->analyzerIs('Analyzer\\Constants\\CustomConstantUsage')
             ->hasNoConstantDefinition();
        $this->prepareQuery();
        
    }
}

?>