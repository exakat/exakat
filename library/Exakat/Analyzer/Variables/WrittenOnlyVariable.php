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

class WrittenOnlyVariable extends Analyzer {
    
    public function dependsOn() {
        return array('Variables/IsModified',
                     'Variables/IsRead',
                    );
    }
    
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('BLOCK')
             ->raw(<<<GREMLIN
local(
    __.sideEffect{ x = [];}
      .repeat(__.out($this->linksDown)).emit().times($MAX_LOOPING).hasLabel("Variable", "Variableobject", "Variablearray")
      .where( __.in("ANALYZED").has("analyzer", "Variables/IsRead"))
      .sideEffect{x.add(it.get().value("code"));}
      .barrier()
    )
    .select("first")
    .repeat(__.out($this->linksDown)).emit().times($MAX_LOOPING).hasLabel("Variable", "Variableobject", "Variablearray")
    .filter{ !(it.get().value("code") in x)}
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
