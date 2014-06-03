<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UnusedUse extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Namespaces\\UsedUse");
    }

    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->analyzerIsNot('Analyzer\\Namespaces\\UsedUse');
    }
}

?>