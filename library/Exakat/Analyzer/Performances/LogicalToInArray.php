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

class LogicalToInArray extends Analyzer {
    public function analyze() {
        // $a == 'a' || $a == 'b'
        // ($a == 'a') || ($a == 'b')
        // $a == 'a' || $b == 'b' || $a == 'c'
        $this->atomIs('Logical')
             ->tokenIs(array('T_LOGICAL_OR', 'T_BOOLEAN_OR'))

             ->outIs('LEFT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('==', '==='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'name')
             ->inIs(array('LEFT', 'RIGHT'))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::$LITERALS)
             ->back('first')
             
             ->raw('emit().repeat( __.out("RIGHT", "LEFT")).times('.self::MAX_LOOPING.').hasLabel("Logical").has("token", within("T_LOGICAL_OR", "T_BOOLEAN_OR"))')
             
             ->outIs('RIGHT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('==', '==='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::$CONTAINERS)
             ->samePropertyAs('fullcode', 'name')
             ->inIs(array('LEFT', 'RIGHT'))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::$LITERALS)
             
             ->back('first');
        $this->prepareQuery();
    }
}

?>
