<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->raw('sideEffect{ if (it.get().properties("reference").any()) { reference = it.get().value("reference");} else { reference = false; }}')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->raw('filter{ if (it.get().properties("reference").any()) { reference != it.get().value("reference");} else { reference != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching argument count :
        // abstract : exact count
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->savePropertyAs('count', 'count')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->is('abstract', true) //then, it is not private
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->notSamePropertyAs('count', 'count')
             ->back('first');
        $this->prepareQuery();

        // non-matching argument count :
        // non-abstract : count may be more but not less
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->savePropertyAs('count', 'count')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->isNot('abstract', true)
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->isMore('count', 'count')
             ->back('first');
        $this->prepareQuery();

        // non-matching typehint
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->raw('sideEffect{ if (it.get().vertices(OUT, "TYPEHINT").any()) { typehint = it.get().vertices(OUT, "TYPEHINT").next().value("fullnspath");} else { typehint = false; }}')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->raw('filter{ if (it.get().vertices(OUT, "TYPEHINT").any()) { typehint != it.get().vertices(OUT, "TYPEHINT").next().value("fullnspath");} else { typehint != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching return typehint
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->raw('sideEffect{ if (it.get().vertices(OUT, "RETURNTYPE").any()) { typehint = it.get().vertices(OUT, "RETURNTYPE").next().value("fullnspath");} else { typehint = false; }}')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->raw('filter{ if (it.get().vertices(OUT, "RETURNTYPE").any()) { typehint != it.get().vertices(OUT, "RETURNTYPE").next().value("fullnspath");} else { typehint != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching nullable
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->raw('sideEffect{ if (it.get().vertices(OUT, "NULLABLE").any()) { nullable = it.get().vertices(OUT, "NULLABLE").next().value("fullnspath");} else { nullable = false; }}')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->raw('filter{ if (it.get().vertices(OUT, "NULLABLE").any()) { nullable != it.get().vertices(OUT, "NULLABLE").next().value("fullnspath");} else { nullable != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching return nullable
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->raw('sideEffect{ if (it.get().vertices(OUT, "NULLABLE").any()) { nullable = it.get().vertices(OUT, "NULLABLE").next().value("fullnspath");} else { nullable = false; }}')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->raw('filter{ if (it.get().vertices(OUT, "NULLABLE").any()) { nullable != it.get().vertices(OUT, "NULLABLE").next().value("fullnspath");} else { nullable != false; }}')
             ->back('first');
        $this->prepareQuery();

        // non-matching visibility
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->raw('sideEffect{ if (it.get().properties("visibility").any()) { v = it.get().value("visibility");} else { v = false; }}')
             ->goToClass()
             ->hasOut('EXTENDS')
             ->goToAllParents()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
             ->inIs('NAME')
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
