<?php

namespace Analyzer\Files;

use Analyzer;

class NotDefinitionsOnly extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Files/DefinitionsOnly',
                     'Files/GlobalCodeOnly');
    }
    
    public function analyze() {
        $this->atomIs("File")
             ->analyzerIsNot('Analyzer\\Files\\DefinitionsOnly')
             ->analyzerIsNot('Analyzer\\Files\\GlobalCodeOnly');
        $this->prepareQuery();
    }
}

?>
