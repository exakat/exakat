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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class RandomlySortedLiterals extends Analyzer {
    public function analyze() {
        $arrays = $this->query(<<<GREMLIN
g.V().hasLabel("Arrayliteral")
     .has("constant", true)
     .filter{ it.get().value("count") > 2 }
     .not( where( out("ARGUMENT").has("rank", 0).hasLabel("Void")) )
     .where( __.sideEffect{ liste = [];}
               .out("ARGUMENT")
               .not( hasLabel("Void") )
               .sideEffect{ 
                    if (it.get().label() == "String" || it.get().label() == "Void" ) {
                        liste.add(it.get().value("noDelimiter"));
                     } else {
                        liste.add(it.get().value("fullcode"));
                     }
                }
               .count()
          )
     .map{ liste.sort(false); }
     .groupCount("m").cap("m").toList()[0].findAll{ a,b -> b > 1}.keySet();
GREMLIN
)->toArray();
        if (empty($arrays)) {
            return;
        }

        $this->atomIs('Arrayliteral')
             ->is('constant', true)
             ->outWithRank('ARGUMENT', 0)
             ->atomIsNot('Void')
             ->back('first')
             ->raw('where( __.sideEffect{ liste = [];}
                             .out("ARGUMENT")
                             .not( hasLabel("Void") )
                             .sideEffect{ 
                                  if (it.get().label() == "String"|| it.get().label() == "Void" ) {
                                    liste.add(it.get().value("noDelimiter"));
                                 } else {
                                    liste.add(it.get().value("fullcode"));
                                }
                             }
                             .count())')
             ->raw('filter{ x = ***;  liste.sort(false) in x; }', $arrays)
             //.values() is not needed for tinkergraph
             ->back('first');
        $this->prepareQuery();
    }
}

?>
