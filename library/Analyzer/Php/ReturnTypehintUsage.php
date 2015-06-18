<?php

namespace Analyzer\Php;

use Analyzer;

class ReturnTypehintUsage extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('Function')
             ->hasOut('RETURN');
        $this->prepareQuery();
    }
}

?>
