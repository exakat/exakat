<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Structures\NoEmptyRegex;

class RegexOnCollector extends Analyzer {
    public function analyze() {
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('INDEX', 'VALUE'))
             ->savePropertyAs('fullcode', 'increment')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Assignation')
             ->tokenIs('T_CONCAT_EQUAL')

             ->_as('collection')
             ->outIs('RIGHT')
             ->samePropertyAs('fullcode', 'increment')
             ->back('collection')

             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'collector')
             
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->functioncallIs(NoEmptyRegex::$pregFunctions)
             ->outWithRank('ARGUMENT', 1)
             ->samePropertyAs('fullcode', 'collector')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
