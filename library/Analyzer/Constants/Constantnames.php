<?php

namespace Analyzer\Constants;

use Analyzer;

class Constantnames extends Analyzer\Analyzer {
    public function analyze() {
        // with define
        $this->atomIs('Functioncall')
             ->code('define', false)
             ->inIsnot('METHOD')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 0);
        $this->prepareQuery();

        // with const
        $this->atomIs('Const')
             ->hasNoParent('Class', array('ELEMENT', 'BLOCK'))
             ->outIs('NAME');
        $this->prepareQuery();
    }
}

?>
