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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class CouldUseCompact extends Analyzer {
    public function analyze() {
        // $a = array('a' => $a, 'b' => $b);
        $this->atomIs('Arrayliteral')
             ->raw('not( where( __.out("ARGUMENT").not(hasLabel("Keyvalue", "Void")) ) ) ') // Only keep Keyvalue and void
             ->raw(<<<GREMLIN
not( where( __.out("ARGUMENT").hasLabel("Keyvalue")
                              .out("INDEX")
                              .not(hasLabel("String", "Identifier", "Nsname", "Concatenation").has("noDelimiter"))
    )     )
GREMLIN
) // Only keep String as name
             ->raw(<<<GREMLIN
not( where( __.out("ARGUMENT").hasLabel("Keyvalue")
                              .out("VALUE")
                              .not(hasLabel("Variable"))
    )     )

GREMLIN
) // Only keep variable as value
             ->raw(<<<GREMLIN
not(
    __.where( __.out("ARGUMENT").hasLabel("Keyvalue")
                .where( __.out("INDEX").hasLabel("String", "Identifier", "Nsname", "Concatenation").sideEffect{ name = '$' + it.get().value("noDelimiter"); }.in("INDEX")
                          .out("VALUE").hasLabel("Variable").filter{it.get().value("fullcode") != name}
                      )
            )
)
GREMLIN
) // Only string = variable name
             ->back('first');
        $this->prepareQuery();
    }
}

?>
