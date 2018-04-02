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

namespace Exakat\Analyzer\Melis;

use Exakat\Analyzer\Analyzer;

class RouteConstraints extends Analyzer {
    public function analyze() {
        // The route has a variable but no validation
        $this->atomIs('Keyvalue')   
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIs('route')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('String')
             ->regexIs('noDelimiter', ':[a-zA-Z0-9]+\\\\]')
             ->inIs('VALUE')
             ->inIs('ARGUMENT')
             ->raw('not(where( __.out("ARGUMENT").out("INDEX").has("noDelimiter", "constraints")))');
        $this->prepareQuery();
        
        $this->atomIs('Keyvalue')   
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIs('route')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('String')
             ->regexIs('noDelimiter', ':[a-zA-Z0-9]+\\\\]')
             ->raw('sideEffect{ids = it.get().value("noDelimiter").findAll(/:([a-zA-Z0-9]+)\]/){match, group -> group }; }')
             ->inIs('VALUE')
             ->inIs('ARGUMENT')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->noDelimiterIs('constraints')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->raw(<<<GREMLIN
where( __.sideEffect{ liste = []; }
               .out("ARGUMENT").out("INDEX")
               .sideEffect{ liste.add(it.get().value("noDelimiter")); }
               .count()
     )
GREMLIN
)
             ->filter('ids - liste != []')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
