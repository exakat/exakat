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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class ListWithAppends extends Analyzer {
    public function analyze() {
        // list($a[]. $a[], $a[]) = array();
        $this->atomIs('List')
             ->hasIn('LEFT')

             // at least one Arrayappend, for initial filtering
             ->filter(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->atomIs('Arrayappend')
             )

             // several appends to the same array
             ->raw('where( __.sideEffect{ counters = [:]; }
                             .out("ARGUMENT").hasLabel("Arrayappend").out("APPEND")
                             .sideEffect{ if (counters[it.get().value("code")] == null) { counters[it.get().value("code")] = 1; } else { counters[it.get().value("code")]++; } }
                             .fold() )
                    .filter{ counters.findAll{ it.value > 1}.size() > 0}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
