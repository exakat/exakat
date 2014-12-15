<?php

namespace Analyzer\Classes;

use Analyzer;

class TooManyChildren extends Analyzer\Analyzer {
    public function analyze() {
        // class a with extends
        $this->atomIs("Class")
             ->raw('sideEffect{nspath = it.fullnspath;}')
             ->outIs('NAME')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("EXTENDS", "IMPLEMENTS").has("fullnspath", nspath).count() >= 15}')
             ->back('first');
        $this->prepareQuery();    
    }
}

?>
