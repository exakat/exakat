<?php

namespace Analyzer\Type;

use Analyzer;

class Octal extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    function analyze() {
        $this->atomIs('Integer')
             ->regex('code', '0[0-7]+');
    }
}

?>