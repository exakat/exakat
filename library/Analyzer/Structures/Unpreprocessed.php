<?php

namespace Analyzer\Structures;

use Analyzer;

class Unpreprocessed extends Analyzer\Analyzer {
    public function analyze() {
        // $x = explode(',', 'a,b,c,d,e,f') => array('a', 'b', 'c',...)
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
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