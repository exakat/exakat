<?php

namespace Analyzer\Type;

use Analyzer;

class SilentlyCastInteger extends Analyzer\Analyzer {
    public function analyze() {
        // Binary or hexadecimal, cast to Float
        $this->atomIs('Float')
             ->regex('code', '0[xXbB]')
             ->back('first');
        $this->prepareQuery();

        // Octal cast to Float
        $this->atomIs('Float')
             ->regex('code', '^0')
             ->regexNot('code', '\\\\.')
             ->back('first');
        $this->prepareQuery();

        // Too long integer
        $this->atomIs('Float')
             ->regex('code', '^[0-9]+\\$')
             ->regexNot('code', '\\\\.')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
