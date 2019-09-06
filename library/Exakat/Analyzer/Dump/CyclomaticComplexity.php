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

namespace Exakat\Analyzer\Dump;

use Exakat\Analyzer\Analyzer;

class CyclomaticComplexity extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = Analyzer::MAX_LOOPING;
        $this->atomIs(Analyzer::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->_as('name')
              ->back('first')
              ->outIs('BLOCK')
              ->raw(<<<GREMLIN
project("cc").by(
    __.emit().repeat( __.out($this->linksDown)).times($MAX_LOOPING).coalesce(
        __.hasLabel(
            "Ifthen", "Case", "Default", "Foreach", "For" ,"Dowhile", "While", "Continue", 
            "Catch", "Finally", "Throw", 
            "Ternary", "Coalesce"
            ),
    __.hasLabel("Ifthen").out("THEN", "ELSE"),
    __.hasLabel("Return").sideEffect{ ranked = it.get().value("rank");}.in("EXPRESSION").coalesce( __.filter{ it.get().value("count") != ranked + 1;},
                                                                                                   __.not(where(__.in("BLOCK").hasLabel("Function"))))
    ).count()
).select("first","cc").by("fullnspath").by()
GREMLIN
);

        $this->analyzerName = 'CyclomaticComplexity';

        $this->prepareQuery(self::QUERY_ARRAYS);


    }
}

?>
