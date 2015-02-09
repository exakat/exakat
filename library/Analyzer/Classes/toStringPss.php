<?php

namespace Analyzer\Classes;

use Analyzer;

class toStringPss extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->hasClass()
             ->outIs('NAME')
             ->code('__toString')
             ->inIs('NAME')
             ->hasOut('STATIC')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Function')
             ->hasClass()
             ->outIs('NAME')
             ->code('__toString')
             ->inIs('NAME')
             ->hasNoOut('PUBLIC')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
