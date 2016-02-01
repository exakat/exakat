<?php

namespace Analyzer\Php;

use Analyzer;

class FopenMode extends Analyzer\Analyzer {
    public function analyze() {
        // fopen('path/to/file', 'bbc')
        $this->atomFunctionIs('\\fopen')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(1)
             ->atomIs('String') // No checks on variable or properties.
             ->hasNoOut('CONTAINS')
             ->noDelimiterIsNot(array('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 't', 't+',  // Normal
                                      'rb', 'rb+', 'wb', 'wb+', 'ab', 'ab+', 'xb', 'xb+', 'cb', 'cb+',   // binary post
                                      'br', 'br+', 'bw', 'bw+', 'ba', 'ba+', 'bx', 'bx+', 'bc', 'bc+'))  // binary pre 
             ->back('first');
        $this->prepareQuery();
    }
}

?>
