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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ShouldUseExplodeArgs extends Analyzer {
    public function analyze(): void {
        // $c = explode('a', $string); array_pop($c)
        $this->atomFunctionis('\\explode')
             ->NoChildWithRank('ARGUMENT', 2)
             ->inIs('RIGHT')
             ->atomIs('Assignation')

             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->inIs('LEFT')

             ->nextSibling('EXPRESSION')
             ->functioncallIs('\\array_pop')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // $c = explode('a', $string); array_slice($c, 0, -3)
        $this->atomFunctionis('\\explode')
             ->NoChildWithRank('ARGUMENT', 2)
             ->inIs('RIGHT')
             ->atomIs('Assignation')

             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->inIs('LEFT')

             ->nextSibling('EXPRESSION')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->functioncallIs('\\array_slice')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->isLess('intval', 0)
             ->back('first');
        $this->prepareQuery();

        // list($a, $b, ) = explode('a', $string);
        $this->atomFunctionis('\\explode')
             ->NoChildWithRank('ARGUMENT', 2)
             ->inIs('RIGHT')
             ->atomIs('Assignation')

             ->outIs('LEFT')
             ->atomIs('List')
             ->outWithRank('ARGUMENT', 'last')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
