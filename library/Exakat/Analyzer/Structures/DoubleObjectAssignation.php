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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DoubleObjectAssignation extends Analyzer {
    public function analyze() {
        // $a = $b = new C;
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(array('New', 'Clone'))
             ->back('first');
        $this->prepareQuery();

        // $a = $b = foo(); function foo() : A
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIsNot(array('Scalartypehint', 'Void', 'Null'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
