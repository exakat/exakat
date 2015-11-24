<?php

namespace Analyzer\Structures;

use Analyzer;

class TimestampDifference extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Addition')
             ->code('-')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Functioncall')
             ->fullnspath(array('\\time', '\\microtime'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
