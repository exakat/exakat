<?php

namespace Analyzer\Structures;

use Analyzer;

class GlobalOutsideLoop extends Analyzer\Analyzer {
    public function analyze() {
        // inside a For//
        $this->atomIs(array('For', 'Foreach', 'Dowhile', 'While'))
             ->outIs('BLOCK')
             ->atomInside('Global');
        $this->prepareQuery();
    }
}

?>
