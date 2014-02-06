<?php

namespace Analyzer\Type;

use Analyzer;

class Continents extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    public function analyze() {
        $ini = $this->loadIni('Continents_en.ini');
        
        $this->atomIs('String')
             ->fullcodeTrimmed(array_values($ini['continents_en']));
    }
}

?>