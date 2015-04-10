<?php

namespace Analyzer\Structures;

use Analyzer;

class CaseAfterDefault extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Switch')
             ->outIs('CASES')
             ->outIs('ELEMENT')
             ->atomIs('Default')
             ->savePropertyAs('rank', 'theDefault')
             ->inIs('ELEMENT')
             ->outIs('ELEMENT')
             ->atomIs('Case')
             ->isMore('rank', 'theDefault')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
