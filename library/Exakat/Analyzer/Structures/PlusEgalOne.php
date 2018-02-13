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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class PlusEgalOne extends Analyzer {
    public function analyze() {
        // $a += 1; $b -= -1;
        $this->atomIs('Assignation')
             ->codeIs(array('+=', '-='))
             ->outIs('RIGHT')
             ->codeIs(array('1', '-1'))
             ->back('first');
        $this->prepareQuery();

        // $a = 1 + $a;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'A')
             ->back('first')
             ->outIs('RIGHT')
             ->atomIs('Addition')
             ->_as('B')
             ->outIs('LEFT')
             ->codeIs(array('1', '-1'))
             ->back('B')
             ->outIs('RIGHT')
             ->samePropertyAs('fullcode', 'A')
             ->back('first');
        $this->prepareQuery();

        // $b = -1 + $b;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'A')
             ->back('first')
             ->outIs('RIGHT')
             ->atomIs('Addition')
             ->_as('B')
             ->outIs('RIGHT')
             ->codeIs(array('1', '-1'))
             ->back('B')
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'A')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
