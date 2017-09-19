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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class GettingLastElement extends Analyzer {
    public function analyze() {
        // current(array_slice($a, -1));
        $this->atomFunctionIs('\\current')
             ->outWithRank('ARGUMENT', 0)
             ->functioncallIs('\\array_slice')
             ->outWithRank('ARGUMENT', 1)
             ->codeIs('-1')
             ->back('first');
        $this->prepareQuery();

        // array_slice($a, -1)[0];
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->codeIs('0')
             ->back('first')
             ->outIs('VARIABLE')
             ->functioncallIs(array('\\array_slice', '\\array_reverse'))
             ->outWithRank('ARGUMENT', 1)
             ->codeIs('-1')
             ->back('first');
        $this->prepareQuery();

        //$b = array_reverse($a)[0];
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->codeIs('0')
             ->back('first')
             ->outIs('VARIABLE')
             ->functioncallIs('\\array_reverse')
             ->back('first');
        $this->prepareQuery();

        //$b = array_pop($a);$a[] = $b;
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomFunctionIs('\\array_pop')
             ->outWithRank('ARGUMENT', 0)
             ->savePropertyAs('code', 'var')
             ->back('first')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('APPEND')
             ->samePropertyAs('code', 'var')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
