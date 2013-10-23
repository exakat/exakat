<?php

namespace Analyzer\Classes;

use Analyzer;

class MethodDefinition extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Ppp")
             ->out('DEFINE')
             ->atomIs('Function')
             ->out('NAME');
        $this->prepareQuery();
        
        $this->atomIs("Class")
             ->out('BLOCK')
             ->out('CODE')
             ->out('ELEMENT')
             ->atomIs('Function')
             ->out('NAME');
        $this->prepareQuery();
    }
}

?>