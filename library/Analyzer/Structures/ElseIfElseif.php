<?php

namespace Analyzer\Structures;

use Analyzer;

class ElseIfElseif extends Analyzer\Analyzer {
    // if () {} else  if {}
    // but not if () {} elseif {}
    public function analyze() {
        $this->atomIs('Ifthen')
             ->outIs('ELSE')
             ->raw('filter{ it.out("ELEMENT").count() == 1}')
             ->outIs('ELEMENT')
             ->atomIs('Ifthen')
             ->tokenIsNot('T_ELSEIF');
        $this->prepareQuery();
    }
}

?>
