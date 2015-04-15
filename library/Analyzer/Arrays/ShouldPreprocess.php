<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Arrays;

use Analyzer;

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        // in case this is the first one in the sequence
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->code('=')
             ->is('rank', 0)
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->raw('filter{ it.out("RIGHT").out.loop(1){true}{it.object.atom == "Variable" && it.object.fullcode == tableau}.any() == false}')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->code('=')
             ->isNot('rank', 0)
             ->_as('main')
             ->nextSibling()
             ->atomIs('Assignation')
             ->code('=')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->raw('filter{ it.out("RIGHT").out.loop(1){true}{it.object.atom == "Variable" && it.object.fullcode == tableau}.any() == false}')
             ->back('main')
             ->previousSibling()
             ->raw('filter{ it.atom != "Assignation" || it.code != "=" || it.out("LEFT").has("atom", "Array").any() == false || it.out("LEFT").out("VARIABLE").has("fullcode", tableau).any() == false }')
             ->back('first');
        $this->prepareQuery();

        // same as above with $array[]
        // in case this is the first one in the sequence
        $this->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->is('rank', 0)
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->savePropertyAs('fullcode', 'tableau')
             ->inIs('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->isNot('rank', 0)
             ->_as('main')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->samePropertyAs('fullcode', 'tableau')
             ->back('main')
             ->previousSibling()
             ->raw('filter{ it.atom != "Assignation" || it.out("LEFT").has("atom", "Arrayappend").any() == false || it.out("LEFT").out("VARIABLE").has("fullcode", tableau).any() == false }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
