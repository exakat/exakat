<?php

namespace Analyzer\Type;

use Analyzer;

class Binary extends Analyzer\Analyzer {
    protected $phpVersion = "5.4+";
    
    public function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    public function analyze() {
        $this->atomIs('Integer')
             ->regex('code', '0b[01]+');
    }
}

?>