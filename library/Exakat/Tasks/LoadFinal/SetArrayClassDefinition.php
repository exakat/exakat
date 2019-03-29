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

class SetArrayClassDefinition extends LoadFinal {
    public function run() {
        //$id, $project, $analyzer, $php
        $query = $this->newQuery('SetArrayClassDefinition');
        $query->atomIs('Arrayliteral', Analyzer::WITHOUT_CONSTANTS)
              ->is('count', 2)
              ->outWithRank('ARGUMENT', 1)
              ->atomIs('String', Analyzer::WITH_CONSTANTS)
              ->has('noDelimiter')
              ->savePropertyAs('noDelimiter', 'method')
              ->back('first')
              ->outWithRank('ARGUMENT', 0)
              ->atomIs('String', Analyzer::WITH_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs(array('MAGICMETHOD', 'METHOD'))
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'method', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Set $count links from array to class");
    }
}

?>
