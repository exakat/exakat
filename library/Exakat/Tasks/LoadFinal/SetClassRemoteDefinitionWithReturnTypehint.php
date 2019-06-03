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

class SetClassRemoteDefinitionWithReturnTypehint extends LoadFinal {
    public function run() {
        $query = $this->newQuery('setClassRemoteDefinitionWithTypehint methods');
        $query->atomIs(Analyzer::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->hasOut('RETURNTYPE')
              ->outIs('DEFINITION')
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFAULT')
              ->atomIs(array('Propertydefinition', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->back('first')
              
              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countM = $result->toInt();
        
        $countP = 0;
// This is for method propagation
//        for($i = 0; $i < 2; ++$i) {
            $query = $this->newQuery('setClassRemoteDefinitionWithTypehint properties');
            $query->atomIs(Analyzer::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->hasOut('RETURNTYPE')
              ->outIs('DEFINITION')
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFAULT')
              ->atomIs(array('Propertydefinition', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->hasNoIn('DEFINITION')
              ->_as('member')
              ->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('MEMBER')
              ->savePropertyAs('code', 'name')
              ->back('first')
              
              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member')
              ->returnCount();
            $query->prepareRawQuery();
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
            $countP += $result->toInt();
//        }

        // constant can't be assigned a method results
        display('Set ' . ($countP + $countM) . ' method and properties remote with return typehint');
    }
}

?>
