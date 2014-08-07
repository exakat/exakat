<?php

namespace Analyzer\Classes;

use Analyzer;

class UnresolvedClasses extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->noClassDefinition();
        $this->prepareQuery();
    }
}

?>