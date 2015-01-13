<?php

namespace Analyzer\Files;

use Analyzer;

class GlobalCodeOnly extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("File")
             ->noAtomInside(DefinitionsOnly::$definitions)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
