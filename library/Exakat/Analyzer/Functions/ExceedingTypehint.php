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

class ExceedingTypehint extends Analyzer {
    public function analyze() {
        // interface i { methods i1(), i2(), i3()}
        // function foo(i $i) { $i->i1(); } No i2, not i3. 
        $this->atomIs(self::$FUNCTIONS)
             ->outIs('ARGUMENT')
             ->as('results')
             ->outIs('TYPEHINT')
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Interface'))
             ->collectMethods('methods')
             ->back('results')
             ->filter(
                $this->side()
                     ->initVariable('used', '[]')
                     ->outIs('NAME')
                     ->outIs('DEFINITION')
                     ->inIs('OBJECT')
                     ->outIs('METHOD')
                     ->outIs('NAME')
                     ->raw('sideEffect{ used.add(it.get().value("lccode"));}.fold()')
             )
             ->raw('filter{ (methods - used).size() != 0;}');
        $this->prepareQuery();
    }
}

?>
