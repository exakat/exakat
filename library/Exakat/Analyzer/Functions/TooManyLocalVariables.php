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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class TooManyLocalVariables extends Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             // Collect all arguments
             ->raw('where( __.sideEffect{ arguments = [];}.out("ARGUMENT").optional( out("LEFT")).sideEffect{ arguments.add(it.get().value("code")); }.barrier().select("first") ) ')

             ->outIs('BLOCK')
             ->_as("block")

             // Collect all global keywords
             ->raw('where( __.sideEffect{ globals = [];}
.optional( __.repeat( __.out('.$this->linksDown.') ).emit( hasLabel("Global") ).times('.self::MAX_LOOPING.').hasLabel("Global").out("GLOBAL").hasLabel("Globaldefinition").sideEffect{ globals.add(it.get().value("code")); }
.barrier().select("block") ) )')

             ->raw('where( __.sideEffect{ x = [:];}
.repeat( __.out('.$this->linksDown.') ).emit( hasLabel("Variable") ).times('.self::MAX_LOOPING.').hasLabel("Variable")
.filter{ !(it.get().value("code") in globals) }
.filter{ !(it.get().value("code") in arguments) }
.sideEffect{ 
    a = it.get().value("code"); if (x[a] == null) { x[a] = 1;} else { x[a]++;}
}.barrier()
)
.filter{ x.size() >= 15;}')
             ->back('first');

        $this->prepareQuery();
    }
}

?>
