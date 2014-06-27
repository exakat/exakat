<?php

namespace Analyzer\Structures;

use Analyzer;

class ForgottenWhiteSpace extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Sequence")
             ->is('root', 'true')
             ->outIs('ELEMENT')
             ->hasOrder('first')
             ->regex('code', '^\\\\s+\\$');
        $this->prepareQuery();

        $this->atomIs("Sequence")
             ->is('root', 'true')
             ->outIs('ELEMENT')
             ->hasOrder('last')
             ->regex('code', '^\\\\s+\\$');
        $this->prepareQuery();
    }
}

?>