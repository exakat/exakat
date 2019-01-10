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

class UseCountRecursive extends Analyzer {
    public function analyze() {
        // foreach($a as $b) { $d = $d + count($b); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->savePropertyAs('fullcode', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Functioncall')
             ->functioncallIs('\\count')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Addition')
             ->outIs(array('LEFT', 'RIGHT'))
             ->outWithRank('ARGUMENT', 0)
             ->samePropertyAs('fullcode', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $b) { $d += count($b); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->savePropertyAs('fullcode', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Functioncall')
             ->functioncallIs('\\count')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->codeIs('+=')
             ->outIs('RIGHT')
             ->outWithRank('ARGUMENT', 0)
             ->samePropertyAs('fullcode', 'blind')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
