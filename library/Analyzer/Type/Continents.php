<?php

namespace Analyzer\Type;

use Analyzer;

class Continents extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $ini = parse_ini_file('data/Continents_en.ini');
        
        $this->atomIs('String')
             ->fullcode(array_values($ini['continents_en']));
    }
}

?>