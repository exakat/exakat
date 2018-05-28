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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class CouldCentralize extends Analyzer {
    // Looking for calls to function with identical literals
    public function analyze() {
        $excluded = array('\\\\defined', '\\\\extension_loaded',);
        $excludedList = makeList($excluded);
        
        foreach(range(0, 3) as $i) {
            $query = <<<GREMLIN
g.V().hasLabel("Functioncall", "Exit")
     .has('fullnspath', without($excludedList))
     .where( 
      __.sideEffect{x = [it.get().value('fullnspath')];}
        .out('ARGUMENT').has('rank', $i)
        .hasLabel('String').sideEffect{x.add(it.get().value('fullcode')); }
      )
     .map{ x; }
     .groupCount('m').by{x;}.cap('m')
GREMLIN;
            $res = $this->query($query);
            
            $functions = array();
            $args = array();
            foreach($res[0] as $key => $count) {
                if ($count < 4) { continue; }
                if (preg_match('/^\[(\S+), (.*?)\]$/is', $key, $r)) {
                    $functions[] = $r[1];
                    if (isset($args[$r[1]])) {
                        $args[$r[1]][] = $r[2];
                    } else {
                        $args[$r[1]] = array($r[2]);
                    }
                }
            }
            
            if (empty($args)) {
                continue;
            }
            
            $this->atomFunctionIs($functions)
                 ->analyzerIsNot('Functions/CouldCentralize')
                 ->savePropertyAs('fullnspath', 'name')
                 ->outWithRank('ARGUMENT', $i)
                 ->isHash('fullcode', $args, 'name')
                 ->back('first');
            $this->prepareQuery();

            $this->atomIs('Exit')
                 ->savePropertyAs('fullnspath', 'name')
                 ->outWithRank('ARGUMENT', $i)
                 ->outIsIE('CODE')
                 ->isHash('fullcode', $args, 'name')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
