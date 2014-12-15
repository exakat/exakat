<?php

namespace Analyzer\Structures;

use Analyzer;

class ConstantScalarExpression extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Const")
             ->outIs('VALUE')
             ->atomIsNot(array('Integer', 'String', 'Float', 'Boolean', 'Void', 'Staticconstant', 'Null'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
