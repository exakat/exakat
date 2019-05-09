<?php
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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class UselessDefault extends Analyzer {
    // function foo($a = 1)
    // foo(1); foo(2); foo(3); // always provide the arg
    public function analyze() {
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->hasOut('DEFAULT')
             ->savePropertyAs('rank', 'ranked')
             ->back('first')
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->raw('count().is(gt(2))')
             )
             ->not(
                $this->side()
                     ->outIs('DEFINITION')
                     ->outIsIE('METHOD')
                     ->noChildWithRank('ARGUMENT', 'ranked')
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
