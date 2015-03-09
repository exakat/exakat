<?php

namespace Analyzer\Interfaces;

use Analyzer;

class UnusedInterfaces extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('UsedInterfaces');
    }
    
    public function analyze() {
        $this->atomIs('Interface')
             ->analyzerIsNot('UsedInterfaces');
        $this->prepareQuery();
    }
}

?>
