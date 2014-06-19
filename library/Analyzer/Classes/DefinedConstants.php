<?php

namespace Analyzer\Classes;

use Analyzer;

class DefinedConstants extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the parent level (one level)
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level (one level)
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();
    }
}

?>