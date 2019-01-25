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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class IncompatibleSignature extends Analyzer {
    public function analyze() {

        // non-matching reference
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->raw('sideEffect{ if (it.get().properties("reference").any()) { reference = it.get().value("reference");} else { reference = false; }}')
             ->inIs('ARGUMENT')
             ->outIs('OVERWRITE')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked')
             ->raw('filter{ if (it.get().properties("reference").any()) { reference != it.get().value("reference");} else { reference != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching argument count :
        // abstract : exact count
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->savePropertyAs('count', 'counted')
             ->outIs('OVERWRITE')
             ->is('abstract', true) //then, it is not private
             ->notSamePropertyAs('count', 'counted')
             ->back('first');
        $this->prepareQuery();

        // non-matching argument count :
        // non-abstract : count may be more but not less
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->savePropertyAs('count', 'counted')
             ->outIs('OVERWRITE')
             ->isNot('abstract', true)
             ->isMore('count', 'counted')
             ->back('first');
        $this->prepareQuery();

        // non-matching typehint
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->raw('sideEffect{ if (it.get().vertices(OUT, "TYPEHINT").any()) { typehint = it.get().vertices(OUT, "TYPEHINT").next().value("fullnspath");} else { typehint = false; }}')
             ->inIs('ARGUMENT')
             ->outIs('OVERWRITE')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked')
             ->raw('filter{ if (it.get().vertices(OUT, "TYPEHINT").any()) { typehint != it.get().vertices(OUT, "TYPEHINT").next().value("fullnspath");} else { typehint != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching return typehint
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->raw('sideEffect{ if (it.get().vertices(OUT, "RETURNTYPE").any()) { typehint = it.get().vertices(OUT, "RETURNTYPE").next().value("fullnspath");} else { typehint = false; }}')
             ->outIs('OVERWRITE')
             ->raw('filter{ if (it.get().vertices(OUT, "RETURNTYPE").any()) { typehint != it.get().vertices(OUT, "RETURNTYPE").next().value("fullnspath");} else { typehint != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching nullable
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->raw('sideEffect{ nullable = it.get().properties("nullable").any(); }')
             ->inIs('ARGUMENT')
             ->outIs('OVERWRITE')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked')
             ->raw('filter{ nullable != it.get().properties("nullable").any(); }')
             ->back('first');
        $this->prepareQuery();

        // non-matching return nullable
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->raw('sideEffect{ nullable = it.get().properties("nullable").any(); }')
             ->outIs('OVERWRITE')
             ->raw('filter{ nullable != it.get().properties("nullable").any(); }')
             ->back('first');
        $this->prepareQuery();

        // non-matching visibility
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->raw('sideEffect{ if (it.get().properties("visibility").any()) { v = it.get().value("visibility");} else { v = false; }}')
             ->outIs('OVERWRITE')
             ->raw(<<<GREMLIN
filter{ 
    if (it.get().properties("visibility").any()) { 
        if (v == "private") {
            it.get().value("visibility") in ["protected", "none", "public"];
        } else if (v == "protected") {
            it.get().value("visibility") in ["none", "public"];
        } else {
            false;
        }
    } else { 
        visibility != false; 
    }
}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
