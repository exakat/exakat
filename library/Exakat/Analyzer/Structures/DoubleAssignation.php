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

class DoubleAssignation extends Analyzer {
    public function analyze() {
        // $a = 1; $a = 2;
        $this->atomIs('Sequence')
             ->raw(<<<GREMLIN
where(
    __.sideEffect{ doubles = [:]; }
      .out("EXPRESSION")
      .hasLabel("Assignation")
      .out("LEFT")
      .hasLabel("Variable", "Array", "Member", "Staticproperty")
      .sideEffect{
         rank = it.get().vertices(IN, "LEFT").next().value("rank");
         if (doubles[it.get().value("fullcode")] == null) {
           doubles[it.get().value("fullcode")] = [rank];
         } else {
           doubles[it.get().value("fullcode")].add(rank);
         }
      }
      .fold()
      .filter{
              // check that doubles has expression in multiple lines and lines are following each other
              doubles = doubles.findAll{ a, b -> b.size() > 1}
                               .findAll{a,b -> b.intersect( b.collect{it2 -> it2 + 1}).size() > 0}
                               .values();
              following = doubles.collect{it3 -> it3.collect{ it4 -> it4 + 1}.intersect(it3)}
                               .flatten(); 
              doubles = doubles.collect{it3 -> it3 - it3.collect{ it4 -> it4 + 1}}
                               .flatten(); 
              // check if there are any result finally
              doubles.size() > 0 ; 
             }  
)

GREMLIN
)
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->filter('it.get().value("rank") in doubles')
             ->initVariable('ranked')
             ->raw('sideEffect{ranked = it.get().value("rank") + 1}')
             ->codeIs('=')
             ->_as('results')
             ->outIs('LEFT')
             ->atomIs(array('Variable', 'Array', 'Member', 'Staticproperty'))
             ->savePropertyAs('fullcode', 'name')
             ->inIs('LEFT')
             ->inIs('EXPRESSION')
             ->outWithRank('EXPRESSION', 'ranked')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->isReassigned('name')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
