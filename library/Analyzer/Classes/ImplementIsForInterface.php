<?php

namespace Analyzer\Classes;

use Analyzer;

class ImplementIsForInterface extends Analyzer\Analyzer {
    public function analyze() {
        // class a with implements
        $this->atomIs("Class")
             ->outIs('IMPLEMENTS')
             ->noInterfaceDefinition()
             ->back('first');
        $this->prepareQuery();    
    }
}

?>