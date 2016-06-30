<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
        return array('Classes/IsExtClass',
                     'Composer/IsComposerNsname',
                     'Interfaces/IsExtInterface');
    }
    
    public function analyze() {
        // constants defined at the class level
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->inIs('CONSTANT')
             ->outIs('CLASS')
             ->classDefinition()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Const").out("CONST").out("LEFT").filter{ it.get().value("code").toLowerCase() == constante.toLowerCase(); }.count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // constants defined at the parents level
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->inIs('CONSTANT')
             ->outIs('CLASS')
             ->classDefinition()
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Const").out("CONST").out("LEFT").filter{ it.get().value("code").toLowerCase() == constante.toLowerCase(); }.count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // constants defined at the interface level
        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'constante')
             ->inIs('CONSTANT')
             ->outIs('CLASS')
             ->classDefinition()
             ->goToImplements()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Const").out("CONST").out("LEFT").filter{ it.get().value("code").toLowerCase() == constante.toLowerCase(); }.count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // constants defined in a class of an extension
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->analyzerIs('Classes/IsExtClass')
             ->back('first');
        $this->prepareQuery();

        // constants defined in a class of an vendor library
        $this->atomIs('Staticconstant')
             ->analyzerIs('Composer/IsComposerNsname');
        $this->prepareQuery();
    }
}

?>
