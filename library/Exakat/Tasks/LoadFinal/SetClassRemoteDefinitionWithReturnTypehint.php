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
        $query->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs(array('Propertydefinition', 'Variabledefinition'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RIGHT')
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
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
                  ->outIs('DEFINITION')
                  ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
                  ->inIs('RIGHT')
                  ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
                  ->outIs('LEFT')
                  // can be anythingm really
                  ->inIs('DEFINITION')
                  ->atomIs(array('Variabledefinition' ,'Propertydefinition'), Analyzer::WITHOUT_CONSTANTS)
    // Variable definition ou bien proeprty definition
                  ->outIs('DEFINITION')
                  ->inIs('OBJECT')
                  ->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
                  ->hasNoIn('DEFINITION')
                  ->_as('member')
                  ->outIs('MEMBER')
                  ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
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

        $query = $this->newQuery('setClassRemoteDefinitionWithTypehint constants');
        $query->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->_as('constante')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('CONSTANT')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RIGHT')
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'constante')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countC = $result->toInt();

        display('Set '.($countP + $countM + $countC).' method, constants and properties remote with return typehint');
    }
}

?>
