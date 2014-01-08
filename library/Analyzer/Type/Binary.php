<?php

namespace Analyzer\Type;

use Analyzer;

class Binary extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    function analyze() {
        $this->atomIs('Integer')
             ->regex('code', '0b[01]+');
    }
}

?>