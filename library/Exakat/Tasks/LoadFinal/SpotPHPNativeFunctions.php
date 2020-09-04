<?php declare(strict_types = 1);
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

class SpotPHPNativeFunctions extends LoadFinal {
    private $PHPfunctions = array();

    public function run(): void {
        $count = 0;

        $query = $this->newQuery('SpotPHPNativeFunctions fallingback');
        $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
              ->isNot('absolute', true)
              ->tokenIs('T_STRING')
              ->has('fullnspath')
              ->hasNoIn('DEFINITION')
              ->not(
                $query->side()
                      ->outIs('NAME')
                      ->inIs('DEFINITION')
                      ->inIs('USE')
                      ->atomIs('Usenamespace', Analyzer::WITHOUT_CONSTANTS)
              )
              ->raw('map{ parts = it.get().value("fullnspath").tokenize("\\\\"); name = parts.last().toLowerCase();}')
              ->unique();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $fallingback = array();
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $fallingback = $result->toArray();
        }

        if (!empty($fallingback)) {
            $phpfunctions = array_merge(...$this->PHPfunctions);
            $phpfunctions = array_map('strtolower', $phpfunctions);
            $phpfunctions = array_values($phpfunctions);

            $diff = array_values(array_intersect($fallingback, $phpfunctions));

            $query = $this->newQuery('SpotPHPNativeFunctions update');
            $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
                  ->has('fullnspath')
                  ->isNot('absolute', true)
                  ->tokenIs('T_STRING')
                  ->hasNoIn('DEFINITION')
                  ->not(
                    $query->side()
                         ->outIs('NAME')
                         ->inIs('DEFINITION')
                         ->inIs('USE')
                         ->atomIs('Usenamespace', Analyzer::WITHOUT_CONSTANTS)
                  )
                  ->raw('filter{ name = it.get().value("fullnspath").tokenize("\\\\").last().toLowerCase(); name in *** }', $diff)
                  ->raw('sideEffect{
         fullnspath = "\\\\" + name;
         it.get().property("fullnspath", fullnspath);
         it.get().property("isPhp", true); 
     }')
                  ->returnCount();
            $query->prepareRawQuery();
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $count = $result->toInt();
        }

        display("Set $count functioncall fallingback");
    }

    public function setPHPfunctions(array $phpfunctions): void {
        $this->PHPfunctions = $phpfunctions;
    }
}

?>
