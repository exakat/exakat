<?php

namespace Analyzer\Type;

use Analyzer;

class HttpStatus extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\Integer");
    }
    
    function analyze() {
        $ini = parse_ini_file('data/HttpStatus.ini');
        
        $this->atomIs('Integer')
             ->code(array_keys($ini['code']));
    }
}

?>