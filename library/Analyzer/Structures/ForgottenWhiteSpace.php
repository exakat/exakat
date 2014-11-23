<?php

namespace Analyzer\Structures;

use Analyzer;

class ForgottenWhiteSpace extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Sequence")
             ->is('root', 'true')
             ->outIs('ELEMENT')
             ->hasRank('first')
             ->regex('code', '^\\\\s+\\$');
        $this->prepareQuery();

        $this->atomIs("Sequence")
             ->is('root', 'true')
             ->outIs('ELEMENT')
             ->hasRank('last', 'ELEMENT')
             ->regex('code', '^\\\\s+\\$');
        $this->prepareQuery();
    }
}

?>