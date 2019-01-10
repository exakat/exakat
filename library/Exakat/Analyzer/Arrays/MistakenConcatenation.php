<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class MistakenConcatenation extends Analyzer {
    public function analyze() {
        // $array = array('a', 'b', 'c'. 'd');
        $this->atomIs('Arrayliteral')
             ->hasChildren('String', 'ARGUMENT')
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->hasNoChildren(array_merge(self::$CONTAINERS, self::$FUNCTIONS_ALL, array('Identifier', 'Nsname', 'Cast', 'Parenthesis')), 'CONCAT')
             ->back('first');
        $this->prepareQuery();

        // $array = array(1, 2, 3, 4.5, );
        $this->atomIs('Arrayliteral')
             ->hasChildren('Integer', 'ARGUMENT')
             ->raw('where( __.out("ARGUMENT").hasLabel("Real").count().is(eq(1)) )') // just one
             ->outIs('ARGUMENT')
             ->atomIs('Real')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
