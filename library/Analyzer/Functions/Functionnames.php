<?php

namespace Analyzer\Functions;

use Analyzer;

class Functionnames extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Function")
             ->isNot('lambda')
             ->classIs('Global')
             ->hasNoParent('Interface', array('ELEMENT', 'CODE', 'CODE'))
             ->outIs('NAME');
    }
}

?>