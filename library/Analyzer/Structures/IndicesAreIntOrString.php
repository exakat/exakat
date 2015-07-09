<?php

namespace Analyzer\Structures;

use Analyzer;

class IndicesAreIntOrString extends Analyzer\Analyzer {
    public function analyze() {
        // $x[1.2], $x[true], $x[null];
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs(array('Boolean', 'Null', 'Float'))
             ->back('first');
        $this->prepareQuery();

        // $x['12'] but not $x['012']
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('String')
             ->hasNoOut('CONTAIN')
             ->regex('noDelimiter', '^[1-9][0-9]*\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
