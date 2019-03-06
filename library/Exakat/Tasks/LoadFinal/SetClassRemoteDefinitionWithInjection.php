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

class SetClassRemoteDefinitionWithInjection extends LoadFinal {
    public function run() {
        $query = new Query(0, $this->config->project, 'SetClassRemoteDefinitionWithInjection', null, $this->datastore);
        $query->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('TYPEHINT')
              ->outIs('NAME')
              ->outIs('DEFINITION')
              ->atomIs('Variable', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('RIGHT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('LEFT')
              ->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)

              ->inIs('DEFINITION')
              ->atomIs('Propertydefinition',  Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->addEFrom('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Set $count properties remote with injection");
    }
}

?>
