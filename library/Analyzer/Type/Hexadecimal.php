<?php

namespace Analyzer\Type;

use Analyzer;

class Hexadecimal extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    function analyze() {
        $this->atomIs('Integer')
             ->regex('code', '0[xX][0-9a-fA-F]+');
    }
}

?>