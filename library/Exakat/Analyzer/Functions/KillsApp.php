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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class KillsApp extends Analyzer {
    public function analyze() {
        // first round : only die and exit
        $this->atomIs('Function')
             ->outIs('BLOCK')
             // We need this straight in the main sequence, not deep in a condition
             ->outIs('EXPRESSION')
             ->atomIs('Exit')
             ->back('first');
        $this->prepareQuery();

        // second round
        $this->atomIs('Function')
             ->outIs('BLOCK')
             // We need this straight in the main sequence, not deep in a condition
             ->outIs('EXPRESSION')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->functionDefinition()
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();

        // third round
        $this->atomIs('Function')
             ->outIs('BLOCK')
             // We need this straight in the main sequence, not deep in a condition
             ->outIs('EXPRESSION')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->functionDefinition()
             ->inIs('NAME')
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
