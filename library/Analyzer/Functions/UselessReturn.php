<?php

namespace Analyzer\Functions;

use Analyzer;

class UselessReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('NAME')
             ->code(array('__constructor', '__destructor', '__clone', '__unset'))
             ->inIs('NAME')
             ->raw("filter{ it.out('BLOCK').out.loop(1){it.object.atom != 'Function'}{it.object.atom == 'Return'}.count() > 0}")
             ->back('first');
        $this->prepareQuery();
    }
}

?>
