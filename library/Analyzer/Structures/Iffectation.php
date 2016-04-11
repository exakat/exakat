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

class Iffectation extends Analyzer\Analyzer {
    public function analyze() {
        // if ($a = 1) {} // straight
        $this->atomIs(array('Ifthen', 'Ternary'))
             ->outIs('CONDITION')
             ->_as('results')
             ->outIsIE('CODE')
             ->atomIs('Assignation')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::LITERALS)
             ->back('results');
        $this->prepareQuery();

        // if (($a = 1) && $y == 2) {} // straight
        $this->atomIs(array('Ifthen', 'Ternary'))
             ->outIs('CONDITION')
             ->atomIs('Logical')
             ->outIs(array('LEFT', 'RIGHT'))
             ->outIsIE('CODE')
             ->atomIs('Assignation')
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::LITERALS)
             ->back('results');
        $this->prepareQuery();

        /* Those are assignations inside comparison : can't be mistaken for constants. 
        // if ( 2 == ($a = 1)) {} (deeper inside)
        $this->atomIs(array('Ifthen', 'Ternary'))
             ->outIs('CONDITION')
             ->atomIs('Comparison')
             ->outIs(array('LEFT', 'RIGHT'))
             ->outIsIE('CODE') // in case of Parenthesis
             ->atomIs('Assignation')
             ->_as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::LITERALS)
             ->back('results');
        $this->prepareQuery();
        */
    }
}

?>
