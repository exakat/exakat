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

class Constructor extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('constructor')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('constructor');
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('NAME')
             ->savePropertyAs('code', 'code')
             ->back('first')
             ->outIs('BLOCK')
             ->raw('where( __.out("ELEMENT").hasLabel("Function").out("NAME").has("code", "__construct").count().is(eq(0)) )')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('constructor')
             ->outIs('NAME')
             ->samePropertyAs('code', 'code')
             ->back('constructor');
        $this->prepareQuery();
    }
}

?>
