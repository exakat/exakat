<?php

namespace Analyzer\Type;

use Analyzer;

class MalformedOctal extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Type\\Integer',
                     'Analyzer\\Type\\Real');
    }
    
    public function analyze() {
        // malformed Octals
        $this->atomIs('Integer')
             ->analyzerIs('Analyzer\\Type\\Integer')
             ->regex('code', '^[+-]?0[0-9]+\\$')
             ->regex('code', '[89]');
        $this->prepareQuery();

        // Octals beginning with too many 0
        $this->atomIs('Integer')
             ->analyzerIs('Analyzer\\Type\\Integer')
             ->regex('code', '^[+-]?0[0-9]+\\$')
             ->regex('code', '^00+');
        $this->prepareQuery();

        // integer that is defined but will be too big and will be turned into a float
        $maxSize = (log(PHP_INT_MAX) / log(2)) / 3 + 1;
        $this->atomIs('Float')
             ->analyzerIs('Analyzer\\Type\\Real')
             ->regex('code', '^[+-]?0[0-7]{'.$maxSize.',}\\$');
        $this->prepareQuery();
    }
}

?>
