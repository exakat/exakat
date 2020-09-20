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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ArrayFillWithObjects extends Analyzer {
    public function analyze() : void {
        $fillingFunctions = array('\\array_fill', 
                                  '\\array_pad');

        // array_fill(new X)
        $this->atomFunctionIs($fillingFunctions)
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('New')
             ->back('first');
        $this->prepareQuery();

        // foo(X $x) { array_fill($x); }
        $this->atomFunctionIs($fillingFunctions)
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->atomIsNot(array('Void', 'Scalartypehint', 'Null'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
