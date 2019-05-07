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

class OneLineTwoInstructions extends Analyzer {
    public function analyze() {
        // Two expressions in a row
        // except for break, continue, void and inlineHtml
        $this->atomIs('Sequence')
             ->raw(<<<'GREMLIN'
where(
        __.sideEffect{ lines = [:]; }
          .out("EXPRESSION")
          .not(hasLabel("Global", "Const", "Inlinehtml", "Void", "Break", "Continue"))
          .sideEffect{ 
            if (lines[it.get().value("line")] == null) {
               lines[it.get().value("line")] = 1;
            } else {
               ++lines[it.get().value("line")];
            }
          }
          .fold()
          .filter{lines = lines.findAll{ a, b -> b > 1}; lines.size() > 0 ; } 
)
.local(
    __.sideEffect{ prems = [:];}
    .where( 
        __.out('EXPRESSION')
          .not(hasLabel("Global", "Const", "Inlinehtml", "Void", "Break", "Continue"))
          .filter{ it.get().value("line") in lines}
          .sideEffect{ 
        if (prems[it.get().value("line")] == null) { 
            prems[it.get().value("line")] = it.get();
        } else if (prems[it.get().value("line")].value("rank") > it.get().value("rank")) {
            prems[it.get().value("line")] = it.get();
        }
            }.fold()
    )
    .map{prems.values();}.unfold()
)
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
