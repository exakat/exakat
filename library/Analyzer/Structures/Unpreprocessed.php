<?php

namespace Analyzer\Structures;

use Analyzer;

class Unpreprocessed extends Analyzer\Analyzer {
    public function analyze() {
        // $x = explode(',', 'a,b,c,d,e,f') => array('a', 'b', 'c',...)
        $this->atomIs("Functioncall")
             ->outIs('NAME')
             ->fullnspath(array('\\explode', '\\split'))
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>