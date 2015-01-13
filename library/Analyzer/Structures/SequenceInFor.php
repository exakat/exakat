<?php

namespace Analyzer\Structures;

use Analyzer;

class SequenceInFor extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('For')
             ->outIs('INIT')
             ->atomIs('Arguments')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->atomIs('Arguments')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             ->outIs('FINAL')
             ->atomIs('Arguments')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
