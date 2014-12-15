<?php

namespace Analyzer\Structures;

use Analyzer;

class LoneBlock extends Analyzer\Analyzer {
    public function analyze() {
        // if (1) {{ $b++; }} 
        $this->atomIs("Sequence")
             ->is('block', 'true')
             ->hasIn('ELEMENT');
        $this->prepareQuery();
    }
}

?>
