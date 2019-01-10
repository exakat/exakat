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

class MultipleDefinedCase extends Analyzer {
    public function analyze() {
        // Check that fullcode is the same or not
        $this->atomIs('Switch')
             ->raw('where( __.sideEffect{ counts = [:]; }
                             .out("CASES").out("EXPRESSION").hasLabel("Case").out("CASE").not(hasLabel("String"))
                             .sideEffect{ k = it.get().value("fullcode"); if (counts[k] == null) { counts[k] = 1; } else { counts[k]++; }}
                             .map{ counts.findAll{it.value > 1}; }.unfold().count().is(neq(0))
                              )');
        $this->prepareQuery();

        // Special case for strings (avoiding ' and ")
        $this->atomIs('Switch')
             ->raw('where( __.sideEffect{ counts = [:]; }
                             .out("CASES").out("EXPRESSION").hasLabel("Case").out("CASE").hasLabel("String").where( __.out("CONCAT").count().is(eq(0)) )
                             .sideEffect{ k = it.get().value("noDelimiter"); if (counts[k] == null) { counts[k] = 1; } else { counts[k]++; }}
                             .map{ counts.findAll{it.value > 1}; }.unfold().count().is(neq(0))
                              )');
        $this->prepareQuery();

        // Special case for mix of strings and constants
    }
}

?>
