<?php

namespace Analyzer\Classes;

use Analyzer;

class OverwrittenConst extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Const')
             ->raw('sideEffect{ result = it;}')
             ->outIs('NAME')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->raw('filter{ it.out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                            .loop(2){true}{true}
                            .filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Const").out("NAME").has("code", constante).any() }.any()}')
             ->raw('transform{ result;}');
        $this->prepareQuery();
    }
}

?>
