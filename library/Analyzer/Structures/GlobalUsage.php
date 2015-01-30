<?php

namespace Analyzer\Structures;

use Analyzer;

class GlobalUsage extends Analyzer\Analyzer {
    public function analyze() {
        // global
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable');
        $this->prepareQuery();

        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Assignation')
             ->outIs('LEFT');
        $this->prepareQuery();

        // $GLOBALS as a whole
        $this->atomIs('Variable')
             ->hasNoIn('VARIABLE')
             ->code('$GLOBALS');
        $this->prepareQuery();

        // $GLOBALS as a whole
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->code('$GLOBALS')
             ->inIs('VARIABLE')
             ->outIs('INDEX');
        $this->prepareQuery();
    }
}

?>
