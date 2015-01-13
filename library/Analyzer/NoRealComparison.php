<?php

namespace Analyzer\NoRealComparison;

use Analyzer;

class  extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        $this->atomIs('Identifier')
             ->back('first');
        $this->printQuery();
        $this->prepareQuery();
    }
}

?>
