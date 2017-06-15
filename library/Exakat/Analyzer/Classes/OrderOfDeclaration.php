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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class OrderOfDeclaration extends Analyzer {
    public function analyze() {
        // Use, const, properties and methods
        $this->atomIs('Class')
             ->outIs(array("USE", "METHOD", "CONST", "PPP"))
             ->savePropertyAs('rank', 'rank')
             ->raw('sideEffect{ 
                if (it.get().label() == "Use") {
                    ok = ["Use", "Const", "Ppp", "Method"];
                } else if (it.get().label() == "Const") {
                    ok = ["Const", "Ppp", "Method"];
                } else if (it.get().label() == "Ppp") {
                    ok = ["Ppp", "Method"];
                } else {
                    ok = ["Method"];
                }
              }')
             ->inIs(array("USE", "METHOD", "CONST", "PPP"))
             ->outIs(array("USE", "METHOD", "CONST", "PPP"))
             ->raw('filter{ it.get().value("rank") == rank + 1; }')
             ->raw('filter{ !(it.get().label() in ok); }')
             ->back('first');
        $this->prepareQuery();

    // static / normal ? 
    // private / property / public 
    }
}

?>
