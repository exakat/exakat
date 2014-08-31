<?php

namespace Analyzer\Structures;

use Analyzer;

class OneLineTwoInstructions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Sequence")
             ->outIs('ELEMENT')
             ->_as('report')
             ->atomIsNot(array('Ppp', 'Global', 'Const', 'RawString'))
             ->savePropertyAs('line', 'line_number')
             ->nextSibling()
             ->atomIsNot(array('RawString'))
             ->samePropertyAs('line', 'line_number')
             ->back('report');
        $this->prepareQuery();
    }
}

?>