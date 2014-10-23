<?php

namespace Analyzer\Constants;

use Analyzer;

class BadConstantnames extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('define', false)
             ->inIsnot('METHOD')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', "'3'")
             ->regex('code', '^[\'\\"]__(.*)__[\'\\"]\\$');
    }
}

?>