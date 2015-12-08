<?php

namespace Analyzer\Classes;

use Analyzer;

class NormalProperty extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array('Class', 'Trait'))
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Visibility')
             ->hasNoOut('STATIC')
             ->outIs('DEFINE')
             ->outIsIE('LEFT');
        $this->prepareQuery();
    }
}

?>
