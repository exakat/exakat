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

class UnusedLabel extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        $noGotoUsage = <<<GREMLIN
not( where( __.out("BLOCK").repeat( __.out({$this->linksDown}) ).emit( ).times( $MAX_LOOPING )
              .hasLabel("Goto").out("GOTO")
              .filter{ it.get().value("code") == name} ) 
    )
GREMLIN;
        // inside functions
        $this->atomIs('Gotolabel')
             ->outIs('GOTOLABEL')
             ->savePropertyAs('code', 'name')
             ->goToFunction()
             ->raw($noGotoUsage)
             ->back('first');
        $this->prepareQuery();

        // inside namespaces are not processed here.

        // in the global space
        $query = <<<GREMLIN
g.V().hasLabel("Goto").out("GOTO")
      .not( where( repeat(__.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV()).until(hasLabel("File")).emit()
                    .hasLabel("Function", "Method", "Closure", "Magicmethod") ) )
      .values("code")
      .unique();
GREMLIN;
        $globalLabels = $this->query($query);
        $globalLabels = $globalLabels->toArray();

        if (empty($globalLabels)) {
            return;
        }
        
        $this->atomIs('Gotolabel')
             ->outIs('GOTOLABEL')
             ->savePropertyAs('code', 'name')
             ->hasNoFunction()
             ->codeIsNot($globalLabels, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

    }
}

?>
