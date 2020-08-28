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

class FixFullnspathConstants extends LoadFinal {
    public function run(): void {
        $query = $this->newQuery('fixFullnspathConstants');
        $query->atomIs(array('Identifier', 'Nsname'), Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->_as('identifier')
              ->savePropertyAs('fullnspath', 'cc')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Trait', 'Interface', 'Constant', 'Defineconstant'), Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<'GREMLIN'
coalesce( __.out("ARGUMENT").has("rank", 0), 
          __.hasLabel("Constant").out('NAME'), 
          filter{ true; })
GREMLIN
)
              ->savePropertyAs('fullnspath', 'actual')
              ->raw('filter{ actual != cc; }')
              ->back('identifier')
              ->setProperty('fullnspath', 'actual')
              ->returnCount();
        $query->prepareRawQuery();
        if (!$query->canSkip()) {
            $this->gremlin->query($query->getQuery(), $query->getArguments());
        }

        display('Fixed Fullnspath for Constants');
    }
}

?>
