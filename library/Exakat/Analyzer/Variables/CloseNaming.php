<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class CloseNaming extends Analyzer {
    
    public function analyze() {

        // Variables with a levenstein distance of 1 or less.
        $this->queryDefinition(<<<GREMLIN
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
             ->raw('where( __.sideEffect{ variables = []}.out("BLOCK").repeat( out('.$this->linksDown.') ).emit( hasLabel("Variable")).times('.self::MAX_LOOPING.').filter{ it.get().value("code").length() > 3}.sideEffect{ variables.push(it.get().value("code")); }.fold() )')
             ->raw('sideEffect{ 
    variables = variables.unique().sort();
    found = []; 
    variables.each{ i -> 
        if (variables.findAll{ it != i && ( it != i + "s" && it + "s" != i) && distance( it , i) < 2 }.size() > 0) {
            found.add(i);
        }
    }
}')
            ->atomInside('Variable')
            ->raw('filter{ it.get().value("code") in found}');
        $this->prepareQuery();

        $variables = $this->query(<<<GREMLIN
g.V().hasLabel("Variable", "Variablearray", "Variableobject").values("code").unique();
GREMLIN
);

        // Identical, except for case
        $lowerCaseVariable = array_map('strtolower', $variables->toArray());
        $lowerCaseVariable = array_count_values($lowerCaseVariable);
        $doubles = array_filter($lowerCaseVariable, function($count){ return $count > 1; });
        
        if (!empty($doubles)) {
            $this->atomIs(array("Variable", "Variablearray", "Variableobject"))
                 ->codeIs($doubles);
            $this->prepareQuery();
        }

        // Identical, except for case
        $noUnderscoreVariables = array_map(function($x) { return str_replace('_', '', $x); }, $variables->toArray());
        $noUnderscoreVariables = array_count_values($noUnderscoreVariables);
        $doubles = array_filter($noUnderscoreVariables, function($count){ return $count > 1; });
        
        if (!empty($doubles)) {
            $this->atomIs(array("Variable", "Variablearray", "Variableobject"))
                  ->raw('filter{it.get().value("code").toString()
                                                      .replaceAll( "_", "") in ***}', $doubles);
            $this->prepareQuery();
        }

        // Identical, except for numbers
        $noFigureVariables = array_map(function($x) { return str_replace(range('0', '9'), '', $x); }, $variables->toArray());
        $noFigureVariables = array_count_values($noFigureVariables);
        $doubles = array_filter($noFigureVariables, function($count){ return $count > 1; });
        
        if (!empty($doubles)) {
            $this->atomIs(array("Variable", "Variablearray", "Variableobject"))
                  ->raw('filter{it.get().value("code").toString()
                                                      .replaceAll( "[0-9]", "") in ***}', $doubles);
            $this->prepareQuery();
        }
    }
}

?>
