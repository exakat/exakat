<?php

namespace Analyzer\Constants;

use Analyzer;

class Constantnames extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('define', false)
             ->inIsnot('METHOD')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 0);
        $this->prepareQuery();

        $this->atomIs("Const")
             ->hasNoParent('Class', array('ELEMENT', 'BLOCK'))
             ->outIs('NAME');
    }
}

?>
