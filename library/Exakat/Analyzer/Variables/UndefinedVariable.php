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

namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class UndefinedVariable extends Analyzer {
    public function analyze() {
        // function foo() { $b->c = 2;}
        $this->atomIs(array('Variable', 'Variableobject', 'Variablearray'))
             ->hasNoOut('DEFINITION')
             ->hasNoParent('Catch', array('VARIABLE'))
             ->hasNoParent('Foreach', array('VALUE'))
             ->hasNoParent('Foreach', array('INDEX', 'VALUE'))
             ->hasNoParent('Foreach', array( 'VALUE', 'VALUE'))
             ->hasNoParent('Assignation', 'LEFT')
             ->hasNoParent('List', 'ARGUMENT')
             ->raw(<<<GREMLIN
not(
    __.has("rank")
      .sideEffect{rank = it.get().value("rank");}
      .in("ARGUMENT")
      .hasLabel("Functioncall")
      .in("DEFINITION")
      .out("ARGUMENT")
      .filter{ it.get().value("rank") == rank}
      .has("reference", true)
)
GREMLIN
)
             ->inIs('DEFINITION')
             ->atomIs(self::$FUNCTIONS_ALL)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
