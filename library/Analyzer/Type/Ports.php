<?php

namespace Analyzer\Type;

use Analyzer;

class Ports extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    public function analyze() {
        $ini = parse_ini_file('data/ports.ini');
        
        $this->atomIs('Integer')
             ->code(array_keys($ini['port']));
    }
}

?>