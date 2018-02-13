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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class SliceFirst extends Analyzer {
    public function analyze() {
        $sliceFunctions = array('\array_slice', '\array_splice', '\array_chunk');
        $manipulatingFunctions = array('\\array_change_key_case', '\\array_flip', '\\array_keys', '\\array_values',
                                       '\\array_filter', '\\array_merge', '\\array_merge_recursive',
                                       '\\array_unique', '\\array_walk',
                                       '\\array_map', '\\array_search',
                                      );

        // array_slice(array_values($array), 2, 5);
        $this->atomFunctionIs($sliceFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomInside('Functioncall')
             ->fullnspathIs($manipulatingFunctions)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
