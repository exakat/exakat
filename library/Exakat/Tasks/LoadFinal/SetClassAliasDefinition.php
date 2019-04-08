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

class SetClassAliasDefinition extends LoadFinal {
    public function run() {

        $query = $this->newQuery('MakeClassConstantDefinition direct');
        $query->atomIs(array('Class', 'Interface', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->savePropertyAs('fullnspath', 'fnp')
              ->outIs('DEFINITION')
              ->is('rank', 0)
              ->inIs('ARGUMENT')
              ->atomIs('Classalias', Analyzer::WITHOUT_CONSTANTS)
              ->outWithRank('ARGUMENT', 1)
              ->outIs('DEFINITION')
              ->atomIs(array('Identifier', 'Nsname', 'Newcall', 'Name'), Analyzer::WITHOUT_CONSTANTS)
              ->dedup('')
              ->property('fullnspath', 'fnp')
              ->addEFrom('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Set $count class alias definitions");
    }
}

?>
