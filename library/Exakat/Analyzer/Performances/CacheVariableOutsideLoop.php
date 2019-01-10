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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class CacheVariableOutsideLoop extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified',
                    );
    }
    
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;

        // foreach($a as $b) { $c = }
        // qui n'est jamais modifiée
        $this->atomIs('Foreach')
             ->outIs('BLOCK')
             ->raw(<<<GREMLIN
where(
    __.sideEffect{ x = [:]; }
      .where(
        __.in("BLOCK").out("VALUE").coalesce( __.out("INDEX", "VALUE"), filter{ true; })
          .sideEffect{ x[it.get().value("code")] = 0;}
          .fold()
      )
      .emit().repeat( __.out({$this->linksDown})).times($MAX_LOOPING).hasLabel("Variable", "Variableobject", "Variablearray")
      .sideEffect{ 
        if (x[it.get().value("code")] == null) {
            x[it.get().value("code")] = 1;
        }
      }
      .or(
      __.where( __.in("ANALYZED").has("analyzer", "Variables/IsModified")),
      __.where( __.in("VARIABLE").in("ANALYZED").has("analyzer", "Arrays/IsModified")),
      __.where( __.in("OBJECT").in("ANALYZED").has("analyzer", "Classes/IsModified")),
      )
      .sideEffect{ x[it.get().value("code")] = 0;}
      .fold()
)
.sideEffect{ written = x.findAll{ a,b -> b == 0 }.keySet();}
.filter{ x.findAll{ a,b -> b > 0}.size() > 0}
GREMLIN
                )
                ->atomInsideNoDefinition(self::$FUNCTIONS_CALLS)
                ->hasNoChildren('Void', array('ARGUMENT'))
                ->raw(<<<GREMLIN
      not(
        where(
          __.emit().repeat(__.out({$this->linksDown})).times($MAX_LOOPING).hasLabel("Variable", "Variableobject", "Variablearray")
            .filter{ it.get().value("code") in written;}
      ))
GREMLIN
                  );
        $this->prepareQuery();
    }
}
?>
