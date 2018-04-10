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

class OverwrittenLiterals extends Analyzer {
    public function analyze() {
    
        $equal = $this->dictCode->translate(array('='));
        
        if (empty($equal)) {
            return;
        }

        $MAX_LOOPING = self::MAX_LOOPING;
        $assignations = $this->queryHash(<<<GREMLIN
g.V().hasLabel("Function", "Closure", "Method", "Magicmethod")
     .where( __.sideEffect{ m = [:]; }
     .out("BLOCK")
     .emit( hasLabel("Assignation")).repeat( __.out() ).times($MAX_LOOPING).hasLabel("Assignation")
     .has("code", $equal[0])
     .not( __.where( __.in("EXPRESSION").in("INIT")) )
     .not( __.where( __.in("PPP")) )
     .where( __.out("RIGHT").hasLabel("Integer", "String", "Real", "Null", "Boolean"))
     .out("LEFT").hasLabel("Variable")
     .sideEffect{ 
            if (m[it.get().value("code")] == null) {
                m[it.get().value("code")] = 1;
            } else {
                m[it.get().value("code")]++;
            }
      }.fold())
      .sideEffect{ names = m.findAll{ a,b -> b > 1}.keySet() }
      .filter{ names.size() > 0;}
      .map{ ["key":it.get().value("fullnspath"),"value":names]; }
GREMLIN
        );

        if (empty($assignations)) {
            return;
        }
        
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->savePropertyAs('fullnspath', 'name')
             ->atomInside('Assignation')
             ->codeIs('=')
             ->raw('not( where( __.in("EXPRESSION").in("INIT")) )')
             ->raw('not( where( __.in("PPP")) )')
             ->outIs('RIGHT')
             ->atomIs(array('Integer', 'String', 'Real', 'Null', 'Boolean'))
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->isHash('code', $assignations, 'name');
        $this->prepareQuery();
    }
}

?>
