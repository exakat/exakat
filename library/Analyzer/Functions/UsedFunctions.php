<?php

namespace Analyzer\Functions;

use Analyzer;

class UsedFunctions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}')
             ->raw('filter{it.out("NAME").next().code != ""}')
             ->outIs('NAME')
             ->raw("filter{ g.idx('Functioncall')[['token':'node']].has('fullnspath', it.fullnspath).any() }");
        $this->prepareQuery();

        $ini = $this->loadIni('php_with_callback.ini');
        foreach($ini as $key => $lists) {
            foreach($lists as $id => $function) {
                $ini[$key][$id] = '\\' . $function;
            }
        }
        
        $positions = array(0, 1, 2);
        foreach($positions as $position) {
            $this->atomIs("Functioncall")
                 ->fullnspath($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('order', $position)
                 ->raw('sideEffect{ it.fullnspath = it.noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ); if (it.fullnspath.toString()[0] != "\\\\") {it.fullnspath = "\\\\" + it.fullnspath;}; }')
                 ->ignore();
            $this->prepareQuery();
        }

        $this->atomIs("Functioncall")
             ->fullnspath($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasOrder('last')
             ->raw('sideEffect{ it.fullnspath = it.noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ); if (it.fullnspath.toString()[0] != "\\\\") {it.fullnspath = "\\\\" + it.fullnspath;}; }')
             ->ignore();
        $this->prepareQuery();

        // actual search for the function in the callbacks
        $this->atomIs("Function")
             ->outIs('NAME')
             ->raw('filter{ f = it; g.idx("String")[["token":"node"]].hasNot("fullnspath", null).filter{it.fullnspath == f.fullnspath; }.any()}');
        $this->prepareQuery();
    }
}

?>