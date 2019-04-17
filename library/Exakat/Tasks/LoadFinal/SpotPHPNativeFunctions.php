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


namespace Exakat\Tasks\LoadFinal;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\Query;

class SpotPHPNativeFunctions extends LoadFinal {
    private $PHPfunctions = array();
    
    public function run() {
        $count = 0;

        $query = $this->newQuery('SpotPHPNativeFunctions fallingback');
        $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->hasNoIn('DEFINITION')
              ->raw('filter{ parts = it.get().value("fullnspath").tokenize("\\\\"); parts.size() > 1 }', array(), array())
              ->raw('map{ name = parts.last().toLowerCase();}', array(), array())
              ->unique();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $fallingback = $result->toArray();

        if (!empty($fallingback)) {
            $phpfunctions = array_merge(...$this->PHPfunctions);
            $phpfunctions = array_map('strtolower', $phpfunctions);
            $phpfunctions = array_values($phpfunctions);

            $diff = array_values(array_intersect($fallingback, $phpfunctions));

            $query = $this->newQuery('SpotPHPNativeFunctions update');
            $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
                  ->has('fullnspath')
                  ->hasNoIn('DEFINITION')
                  ->raw('filter{ parts = it.get().value("fullnspath").tokenize("\\\\\\\\"); parts.size() > 1 }', array(), array())
                  ->raw('filter{ name = parts.last().toLowerCase(); name in *** }', array(), array($diff))
                  ->raw('sideEffect{
         fullnspath = "\\\\" + name;
         it.get().property("fullnspath", fullnspath); 
     }', array(), array())
                  ->returnCount();
            $query->prepareRawQuery();
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $count = $result->toInt();
        }

        display("Set $count functioncall fallingback");
    }
    
    public function setPHPfunctions(array $phpfunctions) {
        $this->PHPfunctions = $phpfunctions;
    }
}

?>
