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

class NeverUsedParameter extends Analyzer {
    public function analyze() {
        // foo($a, $b = 2, $c = 3) {}; foo(1,2);
        $this->atomIs('Function')
             ->outIs('ARGUMENT')
             ->hasOut('DEFAULT')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->hasOut('DEFINITION')  // Make sure this is actually a used function
             ->raw('not(where( __.out("DEFINITION").out("ARGUMENT").not(hasLabel("Void")).filter{ it.get().value("rank") == ranked;}))');
        $this->prepareQuery();
    }
}

?>
