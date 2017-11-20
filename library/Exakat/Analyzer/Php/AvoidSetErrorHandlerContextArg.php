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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class AvoidSetErrorHandlerContextArg extends Analyzer {
    public function analyze() {
        // set_error_handler(function($a, $b, $c, $d) {});
        $this->atomFunctionIs('\\set_error_handler')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Closure')
             ->hasChildWithRank('ARGUMENT', 4) // This is the fifth argument
             ->back('first');
        $this->prepareQuery();

        // set_error_handler('a'); function a ($a, $b, $c, $d) {}
        $this->atomFunctionIs('\\set_error_handler')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->inIs('DEFINITION')
             ->hasChildWithRank('ARGUMENT', 4) // This is the fifth argument
             ->back('first');
        $this->prepareQuery();

        // set_error_handler(array('b', 'a')); class b { function a ($a, $b, $c, $d) {} }
        $this->atomFunctionIs('\\set_error_handler')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Arrayliteral')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String')
             ->savePropertyAs('noDelimiter', 'name')
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 0)
             ->inIs('DEFINITION')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->inIs('NAME')
             ->hasChildWithRank('ARGUMENT', 4) // This is the fifth argument
             ->back('first');
        $this->prepareQuery();

        // set_error_handler(array($a, 'a')); class b { function a ($a, $b, $c, $d) {} }
        // not possible ATM
    }
}

?>
