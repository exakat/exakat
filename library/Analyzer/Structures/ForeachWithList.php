<?php

namespace Analyzer\Structures;

use Analyzer;

class ForeachWithList extends Analyzer\Analyzer {
    public $phpVersion = "5.5+";
    
    public function analyze() {
        $this->atomIs("Foreach")
             ->outIs('VALUE')
             ->atomIs('Functioncall')
             ->code('list')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Foreach")
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('VALUE')
             ->atomIs('Functioncall')
             ->code('list')
             ->back('first');
        $this->prepareQuery();
    }
}

?>