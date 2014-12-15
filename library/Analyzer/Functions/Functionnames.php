<?php

namespace Analyzer\Functions;

use Analyzer;

class Functionnames extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Function")
             ->isNot('lambda', 'true')
             ->hasNoParent('Class', array('ELEMENT', 'BLOCK'))
             ->hasNoParent('Interface', array('ELEMENT', 'BLOCK'))
             ->hasNoParent('Trait', array('ELEMENT', 'BLOCK'))
             ->outIs('NAME');
    }
}

?>
