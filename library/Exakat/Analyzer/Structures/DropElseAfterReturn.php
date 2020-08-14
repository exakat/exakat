<?php declare(strict_types = 1);
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

class DropElseAfterReturn extends Analyzer {
    public function analyze(): void {
        //if ($a) { return $a; } else { doSomething(); }
        $this->atomIs('Ifthen')
             ->tokenIsNot('T_ELSEIF')
             ->raw(<<<'GREMLIN'
not(
    where(
        __.in("EXPRESSION")
          .has("count", 1)
          .in("ELSE")
          .hasLabel("Ifthen")
    )
)
GREMLIN
)
             ->outIs('THEN')
             ->outIs('EXPRESSION')
             ->atomIs('Return')
             ->back('first')
             ->outIs('ELSE')
             ->hasNoChildren('Return', array('EXPRESSION'))
             ->back('first');
        $this->prepareQuery();

        //if ($a) { doSomething(); } else { return $a; }
        $this->atomIs('Ifthen')
             ->tokenIsNot('T_ELSEIF')
             ->raw(<<<'GREMLIN'
not(
    where(
        __.in("EXPRESSION")
          .has("count", 1)
          .in("ELSE")
          .hasLabel("Ifthen")
    )
)
GREMLIN
)
             ->outIs('ELSE')
             ->outIs('EXPRESSION')
             ->atomIs('Return')
             ->back('first')
             ->outIs('THEN')
             ->hasNoChildren('Return', array('EXPRESSION'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
