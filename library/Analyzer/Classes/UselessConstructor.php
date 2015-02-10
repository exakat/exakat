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

class UselessConstructor extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\Constructor');
    }

    public function analyze() {
        // class a (no extends, no implements)
        $this->atomIs("Class")
             ->hasNoOut('EXTENDS')
             ->hasNoOut('IMPLEMENTS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // class a with extends, one level
        $this->atomIs("Class")
             ->hasOut('EXTENDS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->hasNoOut('EXTENDS')
             ->hasNoOut('IMPLEMENTS')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\Constructor").any()}.any() == false }')
             ->back('first');
        $this->prepareQuery();

        // class a with extends, two level
        $this->atomIs("Class")
             ->hasOut('EXTENDS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->hasOut('EXTENDS')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\Constructor").any()}.any() == false }')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\Constructor").any()}.any() == false }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
