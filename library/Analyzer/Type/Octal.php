<?php

namespace Analyzer\Type;

use Analyzer;

class Octal extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    public function analyze() {
        $this->atomIs('Integer')
             ->regex('code', '^-?0[0-7]+\\$');
    }
}

?>