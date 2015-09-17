<?php

namespace Analyzer\Structures;

use Analyzer;

class IssetWithConstant extends Analyzer\Analyzer {
    public function analyze() {
        // isset(X[$a]) or isset(Y::X[$a])
        $this->atomFunctionIs('isset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs(array('Identifier', 'Staticconstant'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
