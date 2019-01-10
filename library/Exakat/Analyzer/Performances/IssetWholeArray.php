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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class IssetWholeArray extends Analyzer {
    public function analyze() {
        // isset($a) || isset($a[1])
        $this->atomIs('Isset')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Variablearray')
             ->savePropertyAs('fullcode', 'array')
             ->back('first')
             ->inIsIE('NOT') // Skip not
             ->inIs(array('LEFT', 'RIGHT'))
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->outIsIE('NOT')
             ->atomIs('Isset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('fullcode', 'array')
             ->back('results');
        $this->prepareQuery();

        // isset($a[1][2]) || isset($a[1])
        $this->atomIs('Isset')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIsNot('Variablearray')
             ->savePropertyAs('fullcode', 'array')
             ->back('first')
             ->inIsIE('NOT') // Skip not
             ->inIs(array('LEFT', 'RIGHT'))
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->outIsIE('NOT')
             ->atomIs('Isset')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->samePropertyAs('fullcode', 'array')
             ->back('results');
        $this->prepareQuery();

        // isset($a, $a[1])
        $this->atomIs('Isset')
             ->isNot('count', 1)
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Variablearray')
             ->savePropertyAs('fullcode', 'array')
             ->back('first')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('fullcode', 'array')
             ->back('first');
        $this->prepareQuery();

        // isset($a[1][2], $a[1])
        $this->atomIs('Isset')
             ->isNot('count', 1)
             ->outIs('ARGUMENT')
//             ->savePropertyAs('rank', 'v')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIsNot('Variablearray')
             ->savePropertyAs('fullcode', 'array')
             ->back('first')
             ->outIs('ARGUMENT')
//             ->notSamePropertyAs('rank', 'v')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIsNot('Variablearray')
             ->samePropertyAs('fullcode', 'array')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
