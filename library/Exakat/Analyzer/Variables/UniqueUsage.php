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

class UniqueUsage extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsRead',
                     'Classes/IsRead',
                     'Arrays/IsRead',
                     'Variables/IsModified',
                     'Classes/IsModified',
                     'Arrays/IsModified',
                    );
    }
    
    public function analyze() {
        $MAX_LOOPING  = self::MAX_LOOPING;

        $this->atomIs(self::$FUNCTIONS_ALL)
             ->raw(<<<GREMLIN
where( 
    __.sideEffect{ args = []; }
      .out('ARGUMENT')
      .coalesce( 
         __.out("NAME"), 
         __.filter{ true}
        )
        .sideEffect{args.add(it.get().value("code"));}
        .fold()
)
.where(
    __.sideEffect{ r = [:]; w = [:]; }.repeat( __.out({$this->linksDown}).simplePath()).emit().times($MAX_LOOPING).hasLabel("Variable", "Variableobject", "Variablearray", "Parametername").as('v')
      .filter{ v = it.get().value("code"); !(v in args);}
      .in("ANALYZED")
      .has("analyzer", within("Variables/IsRead", "Classes/IsRead", "Arrays/IsRead","Variables/IsModified", "Classes/IsModified", "Arrays/IsModified" ))
      .sideEffect{
            if (r[v] == null) {
                r[v] = 0;
                w[v] = 0;
            }
            if (it.get().value("analyzer") in ["Variables/IsRead", "Classes/IsRead", "Arrays/IsRead"]){
                r[v]++;
            } else if (it.get().value("analyzer") in ["Variables/IsModified", "Classes/IsModified", "Arrays/IsModified"]){
                w[v]++;
            } else {
                r[v] = r[v] + 0.1;
            }
        }
        .fold()
)
.filter{d = r.keySet().intersect(w.keySet()).findAll{ r[it] + w[it] == 2}; d.size() > 0;}
GREMLIN
)
             ->outIs('BLOCK')
             ->atomInsideNoDefinition(array('Variable', 'Variableobject', 'Variablearray'))
             ->filter('it.get().value("code") in d;');
        $this->prepareQuery();
    }
}

?>
