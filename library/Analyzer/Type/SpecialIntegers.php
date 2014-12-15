<?php

namespace Analyzer\Type;

use Analyzer;

class SpecialIntegers extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    public function analyze() {
        $ini = $this->loadIni('SpecialIntegers.ini');
        
        $this->atomIs('Integer')
             ->code(array_keys($ini['code']));
    }
}

?>
