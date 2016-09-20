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


namespace Analyzer\Structures;

use Analyzer;

class UnusedGlobal extends Analyzer\Analyzer {
    public function analyze() {
        // global in a function
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->goToFunction()
             // Not used as a variable
             ->raw('where( __.repeat( __.out() ).emit(hasLabel("Variable")).times('.self::MAX_LOOPING.').where( __.in("GLOBAL").count().is(eq(0)) ).filter{ it.get().value("code") == theGlobal}.count().is(eq(0)) )')
             ->back('result');
        $this->prepareQuery();

        // global in the global space
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->hasNoFunction()
             ->hasNoClass()
             ->hasNoInterface()
             ->hasNoTrait()
             // Not used as a variable
             ->raw('where( g.V().out("FILE").out("ELEMENT").out("CODE").out("ELEMENT").not(hasLabel("Global", "Function", "Trait", "Class", "Interface"))
                                .repeat( __.out() ).emit(hasLabel("Variable")).times('.self::MAX_LOOPING.').where( __.in("GLOBAL").count().is(eq(0)) ).filter{ it.get().value("code") == theGlobal}.count().is(eq(0)) )')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
