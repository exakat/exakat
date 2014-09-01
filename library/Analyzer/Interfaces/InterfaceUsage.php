<?php

namespace Analyzer\Interfaces;

use Analyzer;

class InterfaceUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Class')
             ->outIs('IMPLEMENTS');
        $this->prepareQuery();
        
        $this->atomIs('Instanceof')
             ->outIs('CLASS');
        $this->prepareQuery();
    }
}

?>