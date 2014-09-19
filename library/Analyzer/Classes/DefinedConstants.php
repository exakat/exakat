<?php

namespace Analyzer\Classes;

use Analyzer;

class DefinedConstants extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass',
                     'Analyzer\\Classes\\IsVendor',
                     'Analyzer\\Interfaces\\IsExtInterface');
    }
    
    public function analyze() {
        // constants defined at the class level
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->interfaceDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined in a class of an extension
        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->analyzerIs('Analyzer\\Classes\\IsExtClass')
             ->back('first');
        $this->prepareQuery();

        // constants defined in a class of an vendor library
        $this->atomIs("Staticconstant")
             ->analyzerIs('Analyzer\\Classes\\IsVendor')
             ->back('first');
        $this->prepareQuery();

        // constants defined at the parent level (one level)
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any() == false; }")
             ->raw("filter{ it.out('EXTENDS').transform{ g.idx('classes').get('path', it.fullnspath).next(); }
                              .loop(2){true}
                                      {it.object.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any();}.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level (one level)
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any() == false; }")
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level (level 2+)
        $this->atomIs("Staticconstant")
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any() == false; }")
             ->classDefinition()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->hasOut('EXTENDS')
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any() == false; }")
             ->raw("filter{ it.out('EXTENDS').transform{ g.idx('interfaces').get('path', it.fullnspath).next(); }
                              .loop(2){ true }
                                      {it.object.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any();}.any(); }")
             ->back('first');
        $this->prepareQuery();
    }
}

?>