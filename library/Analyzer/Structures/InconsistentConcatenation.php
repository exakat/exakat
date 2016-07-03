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


namespace Analyzer\Structures;

use Analyzer;

class InconsistentConcatenation extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Concatenation')
//             ->filter('it.out("CONCAT").filter{it.atom in ["Variable", "Array", "Functioncall", "Property", "Methodcall", "Staticmethodcall", "Staticconsant", "Staticproperty"]}.any()')
             ->raw('where( __.out("CONCAT").hasLabel("Variable", "Array", "Functioncall", "Property", "Methodcall", "Staticmethodcall", "Staticconsant", "Staticproperty").count().is(neq(0)) )')
             ->outIs('CONCAT')
             ->atomIs('String')
             ->hasOut('CONCAT')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
