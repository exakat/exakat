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

class UseArrayFunctions extends Analyzer {
    public function analyze() {
        // foreach($a as $b) { $c[] = $b};
        $this->atomIs(array('For', 'Foreach'))
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(array('Addition', 'Multiplication', 'Variable', 'Array', 'Concatenation'))
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $b) { array_push($c, $b);};
        $this->atomIs(array('For', 'Foreach'))
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->functioncallIs(array('\\array_push'))
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $b) { if ($b == 1) { $c[] = 1;} else { $d[] = $b;}};
        $this->atomIs(array('For', 'Foreach'))
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Ifthen')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
