<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


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
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->interfaceDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined in a class of an extension
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->analyzerIs('Analyzer\\Classes\\IsExtClass')
             ->back('first');
        $this->prepareQuery();

        // constants defined in a class of an vendor library
        $this->atomIs('Staticconstant')
             ->analyzerIs('Analyzer\\Classes\\IsVendor')
             ->back('first');
        $this->prepareQuery();

        // constants defined at the parent level (one level)
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any() == false; }")
             ->raw("filter{ it.out('EXTENDS').transform{ g.idx('classes')[['path':it.fullnspath]].next(); }
                              .loop(2){true}
                                      {it.object.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any();}.any(); }")
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level (one level)
        $this->atomIs('Staticconstant')
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

        // constants defined at the interface level (several interfaces, one level)
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any() == false; }")
             ->outIs('IMPLEMENTS')
             ->outIs('ARGUMENT')
             ->interfaceDefinition()
             ->raw("filter{ it.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any(); }")
             ->back('first');
        $this->prepareQuery();
        
        // constants defined at the interface level (level 2+)
        $this->atomIs('Staticconstant')
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
             ->raw("filter{ it.out('EXTENDS').transform{ g.idx('interfaces')[['path' : it.fullnspath]].next(); }
                              .loop(2){ true }
                                      {it.object.out('BLOCK').out('ELEMENT').has('atom', 'Const').out('NAME').filter{ it.code.toLowerCase() == constante.toLowerCase(); }.any();}.any(); }")
             ->back('first');
        $this->prepareQuery();
    }
}

?>
