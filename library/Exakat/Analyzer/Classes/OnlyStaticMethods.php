<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class OnlyStaticMethods extends Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             // Avoid empty classes
             ->raw('where( __.out("ELEMENT").count().is(neq(0)) )')
             //There are static methods
             ->raw('where( __.out("ELEMENT").hasLabel("Function").where(__.out("STATIC").count().is(eq(1)) ).count().is(neq(0)) )')
             //There are no non-static methods
             ->raw('where( __.out("ELEMENT").hasLabel("Function").where(__.out("STATIC").count().is(eq(0)) ).count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
