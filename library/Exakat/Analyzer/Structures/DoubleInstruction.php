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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DoubleInstruction extends Analyzer {
    public function analyze() {
        $this->atomIs('Sequence')
             ->raw(<<<GREMLIN
where(
    __.sideEffect{ doubles = [:]; }
      .out("EXPRESSION")
      .not(hasLabel('Ifthen', 'Function', 'Class', 'Postplusplus', 'Preplusplus', 'Void'))
      .sideEffect{ 
         if (doubles[it.get().value("fullcode")] == null) {
           doubles[it.get().value("fullcode")] = [it.get().value("rank")];
         } else {
           doubles[it.get().value("fullcode")].add(it.get().value("rank"));
         }
      }
      .fold()
      .filter{
              // check that doubles has expression in multiple lines and lines are following each other
              doubles = doubles.findAll{ a, b -> b.size() > 1}
                               .findAll{a,b -> b.intersect( b.collect{it2 -> it2 + 1}).size() > 0}
                               .values()
                               .collect{it3 -> it3 - it3.collect{ it4 -> it4 + 1}}
                               .flatten(); 
              // check if there are any result finally
              doubles.size() > 0 ; 
             }  
)
GREMLIN
)
             ->outIs('EXPRESSION')
             ->atomIsNot(array('Ifthen', 'Function', 'Class', 'Postplusplus', 'Preplusplus', 'Void'))
             ->filter('it.get().value("rank") in doubles');
        $this->prepareQuery();
    }
}

?>
