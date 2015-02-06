<?php

namespace Analyzer\Functions;

use Analyzer;

class UsedFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\MarkCallable');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}')
             ->raw('filter{it.out("NAME").next().code != ""}')
             ->outIs('NAME')
             ->raw("filter{ g.idx('atoms')[['atom':'Functioncall']].has('fullnspath', it.fullnspath).any() }");
        $this->prepareQuery();

        $this->atomIs('Function')
             ->outIs('NAME')
             ->raw('filter{ f = it; g.idx("atoms")[["atom":"String"]].hasNot("fullnspath", null).filter{it.fullnspath == f.fullnspath; }.any()}');
        $this->prepareQuery();
    }
}

?>
