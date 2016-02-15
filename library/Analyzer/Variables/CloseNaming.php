<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Analyzer\Variables;

use Analyzer;

class CloseNaming extends Analyzer\Analyzer {
    
    public function analyze() {
        $this->query(<<<GREMLIN
  def distance(String str1, String str2) {
    def str1_len = str1.length()
    def str2_len = str2.length()
    int[][] distance = new int[str1_len + 1][str2_len + 1]
    (str1_len + 1).times { distance[it][0] = it }
    (str2_len + 1).times { distance[0][it] = it }
    (1..str1_len).each { i ->
       (1..str2_len).each { j ->
          distance[i][j] = [distance[i-1][j]+1, distance[i][j-1]+1, str1[i-1]==str2[j-1]?distance[i-1][j-1]:distance[i-1][j-1]+1].min()
       }
    }
    distance[str1_len][str2_len]
  }

GREMLIN
);

        $this->atomIs('Function')
             ->raw('sideEffect{ 
    variables = []; it.out.loop(1){ it.loops < 10}{it.object.atom == "Variable"}.filter{ it.code.length() > 3}.code.fill(variables); 
    variables = variables.unique().sort();
    found = []; 
    variables.each{ i -> 
        if (variables.findAll{ it != i && ( it != i + "s" && it + "s" != i) && distance( it , i) < 2 }.size() > 0) {
            found.add(i);
        }
    }
}')
            ->atomInside('Variable')
            ->filter('it.code in found');
        $this->prepareQuery();
    }
}

?>
