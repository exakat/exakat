<?php

namespace Analyzer\Structures;

use Analyzer;

class OneLineTwoInstructions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Sequence")
             ->outIs('ELEMENT')
             ->_as('report')
             ->atomIsNot(array('Ppp', 'Global', 'Const'))
             ->savePropertyAs('line', 'line_number')
             ->nextSibling()
             ->samePropertyAs('line', 'line_number')
             ->back('report');
        $this->prepareQuery();
    }
}

?>