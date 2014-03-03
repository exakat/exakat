<?php

namespace Analyzer\Type;

use Analyzer;

class Email extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $this->atomIs('String')
             ->regex('code', '[_A-Za-z0-9-]+(\\\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\\\.[A-Za-z0-9]+)*(\\\\.[A-Za-z]{2,})');
        $this->prepareQuery();
    }
}

?>