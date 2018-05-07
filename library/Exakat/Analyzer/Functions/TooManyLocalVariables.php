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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class TooManyLocalVariables extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;

        $this->atomIs(self::$FUNCTIONS_ALL)
             // Collect all arguments
             ->raw(<<<GREMLIN
where(  __.sideEffect{ arguments = [];}
          .out("ARGUMENT").optional( out("LEFT"))
          .sideEffect{ arguments.add(it.get().value("code")); }
          .barrier()
          .select("first") 
     ) 
GREMLIN
)

             ->outIs('BLOCK')
             ->_as("block")

             // Collect all global keywords
             ->raw(<<<GREMLIN
where( __.sideEffect{ globals = [];}
         .optional( __.repeat( __.out({$this->linksDown}) ).emit( hasLabel("Global") ).times($MAX_LOOPING)
         .hasLabel("Global").out("GLOBAL")
         .hasLabel("Globaldefinition")
         .sideEffect{ globals.add(it.get().value("code")); }
         .barrier()
         .select("block") )
     )
GREMLIN
)

             ->raw(<<<GREMLIN
where( __.sideEffect{ x = [:];}
         .repeat( __.out({$this->linksDown}) ).emit( hasLabel("Variable") ).times($MAX_LOOPING).hasLabel("Variable")
         .filter{ !(it.get().value("code") in globals) }
         .filter{ !(it.get().value("code") in arguments) }
         .sideEffect{ 
             a = it.get().value("code"); if (x[a] == null) { x[a] = 1;} else { x[a]++;}
         }.barrier()
         )
         .filter{ x.size() >= {$this->tooManyLocalVariableThreshold};}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
