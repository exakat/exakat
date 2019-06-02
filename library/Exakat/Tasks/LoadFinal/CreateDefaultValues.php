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

class CreateDefaultValues extends LoadFinal {
    public function run() {

        // For variables
        $query = $this->newQuery('CreateDefaultValues variables');
        $query->atomIs(array('Variabledefinition', 'Staticdefinition' ,'Globaldefinition'), Analyzer::WITHOUT_CONSTANTS)
              ->hasNoOut('DEFAULT')
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->codeIs('=', Analyzer::TRANSLATE, Analyzer::CASE_SENSITIVE)
              ->outIs('RIGHT')
              ->addEFrom('DEFAULT', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countVar = $result->toInt();
        display("Added $countVar default value for variable");

        // For properties in traits
        $query = $this->newQuery('CreateDefaultValues variables');
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoOut('DEFAULT')
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->codeIs('=', Analyzer::TRANSLATE, Analyzer::CASE_SENSITIVE)
              ->outIs('RIGHT')
              ->addEFrom('DEFAULT', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countVar = $result->toInt();
        display("Added $countVar default value for properties");
    }
}

?>
