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

class SpotExtensionNativeFunctions extends LoadFinal {
    public function run(): void {
        $query = $this->newQuery('SpotExtensionNativeFunctions fallingback');
        $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
              ->isNot('absolute', true)
              ->tokenIs('T_STRING')
              ->has('fullnspath')
              ->hasNoIn('DEFINITION')
              ->raw('filter{ parts = it.get().value("fullnspath").tokenize("\\\\"); parts.size() > 1 }')
              ->raw('map{ name = "\\\\" + parts.last().toLowerCase();}')
              ->unique();
        $query->prepareRawQuery();
        if ($query->canSkip()) {
            $fallingback = array();
        } else {
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $fallingback = $result->toArray();
        }

        if (empty($fallingback)) {
            display('Set 0 extension functioncall fallingback');
            return;
        }

        $functionsDev = $this->config->dev->loadIni('functions.ini', 'functions');
        $functionsExt = $this->config->ext->loadIni('functions.ini', 'functions');
        $functionsAll = array_unique(array_merge($functionsDev, $functionsExt));
        $diff = array_values(array_intersect($fallingback, $functionsAll));

        $query = $this->newQuery('SpotExtensionNativeFunctions update');
        $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->isNot('absolute', true)
              ->tokenIs('T_STRING')
              ->hasNoIn('DEFINITION')
              ->raw('filter{ parts = it.get().value("fullnspath").tokenize("\\\\"); parts.size() > 1 }')
              ->raw('filter{ name = "\\\\" + parts.last().toLowerCase(); name in *** }', $diff)
              ->raw('sideEffect{
         it.get().property("fullnspath", name); 
     }')
                  ->returnCount();
         $query->prepareRawQuery();
         $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
         $count = $result->toInt();

        display("Set $count extension functioncall fallingback");
    }
}

?>
