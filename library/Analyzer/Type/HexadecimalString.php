<?php

namespace Analyzer\Type;

use Analyzer;

class HexadecimalString extends Analyzer\Analyzer {
    public function analyze() {
        $regex = '^\\\\s*0[xX][0-9a-fA-F]+';
        // Strings
        $this->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->regex('noDelimiter', $regex);
        $this->prepareQuery();

        // Concatenation String
        $this->atomIs('String')
             ->outIs('CONTAINS')
             ->is('rank', 0)
             ->atomIs('String')
             ->regex('noDelimiter', $regex)
             ->back('first');
        $this->prepareQuery();

        // Simple Heredoc and nowdoc
        $this->atomIs('Heredoc')
             ->outIs('CONTAINS')
             ->hasNoOut('CONTAINS')
             ->regex('noDelimiter', $regex);
        $this->prepareQuery();

        // Concatenation Heredoc
        $this->atomIs('Heredoc')
             ->outIs('CONTAINS')
             ->outIs('CONTAINS')
             ->is('rank', 0)
             ->atomIs('String')
             ->regex('noDelimiter', $regex)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
