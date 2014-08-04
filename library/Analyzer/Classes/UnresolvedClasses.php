<?php

namespace Analyzer\Classes;

use Analyzer;

class UnresolvedClasses extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->atomIs("New")
             ->outIs('NEW')
             ->is("fullnspath", '');
        $this->prepareQuery();
    }
}

?>