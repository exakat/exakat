<?php

namespace Analyzer\Type;

use Analyzer;

class SpecialInteger extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    function analyze() {
        $ini = parse_ini_file('data/SpecialIntegers.ini');
        
        $this->atomIs('Integer')
             ->code(array_keys($ini['code']));
    }
}

?>