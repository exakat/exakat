<?php

namespace Analyzer\Classes;

use Analyzer;

class NormalMethods extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array('Class', 'Trait'))
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasNoOut('STATIC')
             ->outIs('NAME');
        $this->prepareQuery();
    }
}

?>
