<?php

namespace Analyzer\Functions;

use Analyzer;

class UsedFunctions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}')
             ->raw('filter{it.out("NAME").next().code != ""}')
             ->outIs('NAME')
             ->raw("filter{ g.idx('atoms')[['atom':'Functioncall']].has('fullnspath', it.fullnspath).any() }");
        $this->prepareQuery();

        $ini = $this->loadIni('php_with_callback.ini');
        foreach($ini as $key => $lists) {
            foreach($lists as $id => $function) {
                $ini[$key][$id] = '\\' . $function;
            }
        }

        // callable is in # position
        $positions = array(0, 1, 2, 3, 4, 5, 6);
        foreach($positions as $position) {
            $this->atomIs("Functioncall")
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspath($ini['functions'.$position])
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('String')
                 ->tokenIsNot('T_QUOTE')
                 ->raw('sideEffect{ it.fullnspath = it.noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ); if (it.fullnspath == "" || it.fullnspath.toString()[0] != "\\\\") {it.fullnspath = "\\\\" + it.fullnspath;}; }')
                 ->ignore();
            $this->prepareQuery();
        }

        // callable is in last
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('last')
             ->atomIs('String')
             ->raw('sideEffect{ it.fullnspath = it.noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ); if (it.fullnspath.toString()[0] != "\\\\") {it.fullnspath = "\\\\" + it.fullnspath;}; }')
             ->ignore();
        $this->prepareQuery();

        // callable is in 2nd to last
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini['functions_2last'])
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank('2last')
             ->atomIs('String')
             ->raw('sideEffect{ it.fullnspath = it.noDelimiter.toLowerCase().replaceAll( "\\\\\\\\\\\\\\\\", "\\\\\\\\" ); if (it.fullnspath.toString()[0] != "\\\\") {it.fullnspath = "\\\\" + it.fullnspath;}; }')
             ->ignore();
        $this->prepareQuery();

        // actual search for the function in the callbacks
        $this->atomIs("Function")
             ->outIs('NAME')
             ->raw('filter{ f = it; g.idx("atoms")[["atom":"String"]].hasNot("fullnspath", null).filter{it.fullnspath == f.fullnspath; }.any()}');
        $this->prepareQuery();
    }
}

?>