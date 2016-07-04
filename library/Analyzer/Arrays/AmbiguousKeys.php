<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Arrays;

use Analyzer;

class AmbiguousKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomFunctionIs('\\array')
             ->raw('where(
    __.sideEffect{ counts = [:]; integers = [:]; strings = [:]; }
      .out("ARGUMENTS").out("ARGUMENT").hasLabel("Keyvalue").out("KEY")
      .hasLabel("String", "Integer").where(__.out("CONCAT").count().is(eq(0)))
      .sideEffect{ 
            if ("noDelimiter" in it.get().keys() ) { 
                k = it.get().value("noDelimiter"); 
                if (strings[k] == null) { strings[k] = 1; } else { strings[k]++; }
                if (integers[k] != null) { integers[k] = 1; }
            } else { 
                k = it.get().value("code"); 
                if (integers[k] == null) { integers[k] = 1; } else { integers[k]++; }
                if (strings[k] != null) { counts[k] = 1; }
            }
        }
        .map{ counts; }.unfold().count().is(neq(0))
)'
);
        $this->prepareQuery();
    }
}

?>
