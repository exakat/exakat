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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class CouldTypeWithIterable extends Analyzer {
    public function analyze() {
        // Used in a foreach
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('SOURCE')
             ->atomIs('Foreach')
             ->back('first');
        $this->prepareQuery();

        // Used in a yield from
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('YIELD')
             ->atomIs('Yieldfrom')
             ->back('first');
        $this->prepareQuery();

        // Used with variadic foo($a) { ...$a; }
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variable')
             ->is('variadic', true)
             ->back('first');
        $this->prepareQuery();

        // function foo($a) { bar($a); } function bar(iterable $b) {}
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'ranked')
             ->outIs('TYPEHINT')
             ->fullnspathIs('\\iterable')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
