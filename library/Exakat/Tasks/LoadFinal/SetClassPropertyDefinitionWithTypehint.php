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

class SetClassPropertyDefinitionWithTypehint extends LoadFinal {
    public function run() {
        $query = $this->newQuery('SetClassPropertyDefinitionWithTypehint method');
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('call')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'call')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countM = $result->toInt();

        $query = $this->newQuery('SetClassPropertyDefinitionWithTypehint property');
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('call')
              ->outIs('MEMBER')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'call')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countP = $result->toInt();

        $query = $this->newQuery('SetClassPropertyDefinitionWithTypehint constants');
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('DEFINITION')
              ->inIs('CLASS')
              ->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->_as('call')
              ->outIs('CONSTANT')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'call')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countC = $result->toInt();

        $count = $countP + $countM;
        display("Set $count method, class and properties with typehinted properties");
        $this->log->log(__METHOD__);
    }
}

?>
