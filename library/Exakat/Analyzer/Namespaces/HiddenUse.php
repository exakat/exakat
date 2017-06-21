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

namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class HiddenUse extends Analyzer {
    public function analyze() {
        // only for uses with rank of 1 or later
        $this->atomIs('Use')
             ->savePropertyAs('rank', 'rank')
             ->inIs('EXPRESSION')
             ->raw('where( __.out("EXPRESSION").not(hasLabel("Use")).filter{ it.get().value("rank") < rank} )')
             ->back('first');
        $this->prepareQuery();
        
        // rank = 0 use are OK
        // inside a class/trait
        $this->atomIs('Use')
             ->savePropertyAs('rank', 'rank')
             ->inIs('USE')
             ->raw('where( __.out("CONST", "METHOD", "PPP").filter{ it.get().value("rank") < rank} )')
             ->back('first');
        $this->prepareQuery();
        
    }
}

?>
