<?php

namespace Analyzer\Structures;

use Analyzer;

class BooleanStrictComparison extends Analyzer\Analyzer {
    public function analyze() {
        // while (list($a, $b) = each($c)) {}
        $this->atomIs("Comparison")
             ->outIs(array('RIGHT', 'LEFT'))
             ->atomIs('Boolean')
             ->back('first')
             ->codeIsNot(array('===', '!=='));
        $this->prepareQuery();
    }
}

?>
