<?php

namespace Analyzer\Files;

use Analyzer;

class GlobalCodeOnly extends Analyzer\Analyzer {
    /* Remove this is useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        $this->atomIs("File")
             ->noAtomInside(DefinitionsOnly::$definitions)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
