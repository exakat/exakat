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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class IndicesAreIntOrString extends Analyzer {
    public function analyze() {
        // $x[1.2], $x[true], $x[null];
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs(array('Boolean', 'Null', 'Real'))
             ->back('first');
        $this->prepareQuery();

        // $x['12'] but not $x['012']
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('String')
             ->tokenIsNot('T_NUM_STRING')  // a string but a real integer
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '^[1-9][0-9]*\\$')
             ->back('first');
        $this->prepareQuery();

        // $x[a] and const a = 2.3
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs(array('Identifier', 'Nsname', 'Staticconstant'))
             ->inIs('DEFINITION')
             ->outIs('VALUE')
             ->atomIs(array('Boolean', 'Real', 'Null', 'Functioncall')) // Functioncall is for array
             ->back('first');
        $this->prepareQuery();

        // $x[a] and define('a', 2.3)
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs(array('Identifier', 'Nsname'))
             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs(array('Boolean', 'Real', 'Null', 'Functioncall')) // Functioncall is for array
//             ->tokenIs(array('T_STRING', 'T_DNUMBER', 'T_ARRAY', 'T_OPEN_BRACKET'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
