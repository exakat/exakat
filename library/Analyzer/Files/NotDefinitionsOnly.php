<?php

namespace Analyzer\Files;

use Analyzer;

class NotDefinitionsOnly extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Files/DefinitionsOnly');
    }
    
    public function analyze() {
        $this->atomIs("File")
             ->analyzerIsNot('Analyzer\\Files\\DefinitionsOnly');
        $this->prepareQuery();
    }
}

?>
