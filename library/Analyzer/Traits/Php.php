<?php

namespace Analyzer\Traits;

use Analyzer;

class Php extends Analyzer\Analyzer {

    public function analyze() {
        $ini = $this->loadIni('php_trait.ini'); 
        
        if (isset($ini['trait'])) {
            $this->analyzerIs("Analyzer\\Interfaces\\TraitUsage")
                 ->code($ini['trait']);
        }
    }
}

?>