<?php

namespace Analyzer\Type;

use Analyzer;

class Hexadecimal extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Integer");
    }
    
    public function analyze() {
        $this->atomIs('Integer')
             ->regex('code', '0[xX][0-9a-fA-F]+');
    }
}

?>