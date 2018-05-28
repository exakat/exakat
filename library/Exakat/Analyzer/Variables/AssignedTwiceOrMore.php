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

class AssignedTwiceOrMore extends Analyzer {
    public function analyze() {
        $list = makeList(self::$FUNCTIONS_ALL);
        $maxLooping = self::MAX_LOOPING;
        $equal = $this->dictCode->translate(array('='));
        
        if (empty($equal)) {
            return;
        }

        $query = <<<GREMLIN
g.V().hasLabel($list).where( 
            __.sideEffect{counts = [:]; names = [];}
              .out("BLOCK").repeat( __.out({$this->linksDown})).emit().times($maxLooping)
              .hasLabel("Assignation").has("code", $equal[0])
              .where( __.out("RIGHT").hasLabel("Integer", "Real", "Boolean", "Null", "Heredoc"))
              .out("LEFT").hasLabel("Variable")
              .sideEffect{ k = it.get().value("fullcode"); 
                           if (counts[k] == null) {
                              counts[k] = 1;
                           } else {
                              counts[k]++;
                           }
                        }
                .fold()
            )
           .sideEffect{ names = counts.findAll{ a,b -> b > 1}.keySet() }
           .filter{ names.size() > 0;}
           .map{ ["key":it.get().value("fullnspath"),"value":names]; }
GREMLIN;
        $variables = $this->queryHash($query);
        
        if (empty($variables)) {
            return;
        }
        
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->savePropertyAs('fullnspath', 'name')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Assignation')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->isHash('fullcode', $variables, 'name');
        $this->prepareQuery();
    }
}

?>
