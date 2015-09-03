<?php

namespace Analyzer\Structures;

use Analyzer;

class ElseUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Ifthen')
             ->hasNoIn('ELSE')
             ->outIs('ELSE')
             ->tokenIsNot('T_ELSEIF')
             ->is('count', 1)
             ->outIs('ELEMENT')
             ->tokenIsNot('T_IF')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
