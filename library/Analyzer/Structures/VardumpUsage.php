<?php

namespace Analyzer\Structures;

use Analyzer;

class VardumpUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code(array('var_dump', 'print_r'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('order', 1)
             ->codeIsNot("true")
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->code(array('var_dump', 'print_r'))
             ->outIs('ARGUMENTS')
             ->noChildWithOrder('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

    }
}

?>