<?php

namespace Analyzer\Portability;

use Analyzer;

class FopenMode extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('fopen')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT',1)
             ->regexNot('code', 'b')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->code('fopen')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 1)
             ->regex('code', 't')
             ->back('first');
        $this->prepareQuery();
    }
}
?>
