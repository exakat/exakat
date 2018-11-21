<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class CompactInexistant extends Analyzer {
    public function analyze() {
        // compact('a', 'b') with $b or $a that doesn't exists
        $this->atomFunctionIs('\\compact')
             ->outIs('ARGUMENT')
             ->_as('results')
             ->has('noDelimiter')
             ->savePropertyAs('noDelimiter', 'variable_name')
             ->makeVariableName('variable_name')
             ->goToFunction()
             ->raw(<<<GREMLIN
not(
    __.where(__.out("DEFINITION").filter{ it.get().value("fullcode") == variable_name; })
)
GREMLIN
)
             ->raw(<<<GREMLIN
not(
    __.where(__.out("ARGUMENT").out("NAME").filter{ it.get().value("fullcode") == variable_name; })
)
GREMLIN
)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
