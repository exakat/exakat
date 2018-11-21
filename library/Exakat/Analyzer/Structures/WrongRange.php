<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class WrongRange extends Analyzer {
    public function analyze() {
        // if ($a > 1 || $a < 1000)
        $this->atomIs('Logical')
             ->codeIs(array('||', 'or'))

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs(array('>', '>='))
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'variable')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->has('intval')
             ->savePropertyAs('intval', 'lowerbound')

             ->back('first')

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs(array('<', '<='))
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->samePropertyAs('fullcode', 'variable')
             ->inIs('LEFT')

             ->outIs('RIGHT')
             ->has('intval')
             ->savePropertyAs('intval', 'upperbound')

             ->filter('lowerbound <= upperbound;')

             ->back('first');
        $this->prepareQuery();

        // if ($a < 1 && $a > 1000)
        $this->atomIs('Logical')
             ->codeIs(array('&&', 'and'))

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs(array('>', '>='))
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'variable')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->has('intval')
             ->savePropertyAs('intval', 'lowerbound')

             ->back('first')

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs(array('<', '<='))
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->samePropertyAs('fullcode', 'variable')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->has('intval')
             ->savePropertyAs('intval', 'upperbound')

             ->filter('lowerbound >= upperbound;')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
