<?php

namespace Analyzer\Type;

use Analyzer;

class MalformedOctal extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Type\\Integer',
                     'Analyzer\\Type\\Real');
    }
    
    public function analyze() {
        $this->atomIs('Integer')
             ->analyzerIs('Analyzer\\Type\\Integer')
             ->regex('code', '^-?0[0-9]+\\$')
             ->regex('code', '[89]');
        $this->prepareQuery();

        $maxSize = (log(PHP_INT_MAX) / log(2)) / 3 + 1;
        $this->atomIs('Float')
             ->analyzerIs('Analyzer\\Type\\Real')
             ->regex('code', '^-?0[0-7]{'.$maxSize.',}\\$');
        $this->prepareQuery();
    }
}

?>
