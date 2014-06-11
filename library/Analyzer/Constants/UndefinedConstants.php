<?php

namespace Analyzer\Constants;

use Analyzer;

class UndefinedConstants extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\ConstantUsage');
    }
    
    public function analyze() {
        $this->atomIs("Identifier")
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->hasNoConstantDefinition();
        $this->prepareQuery();
        
        $this->atomIs("Nsname")
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->hasNoConstantDefinition();
        $this->prepareQuery();
    }
}

?>