<?php

namespace Analyzer\Type;

use Analyzer;

class HttpStatus extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    public function analyze() {
        $ini = $this->loadIni('HttpStatus.ini');
        
        $this->atomIs('Integer')
             ->code(array_keys($ini['code']));
    }
}

?>
