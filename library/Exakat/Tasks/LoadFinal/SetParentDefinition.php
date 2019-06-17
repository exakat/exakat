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

class SetParentDefinition extends LoadFinal {
    public function run() {

        $query = $this->newQuery('SetParentDefinition direct');
        $query->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->_as('parent')
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'parent')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count1 = $result->toInt();

        $query = $this->newQuery('SetParentDefinition property');
        $query->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->_as('parent')
              ->inIs('CLASS')
              ->atomIs('Staticproperty', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('MEMBER')
              ->tokenIs('T_VARIABLE')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'property')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count1 = $result->toInt();
        display("Set $count1 parent property definitions");

        $query = $this->newQuery('SetParentDefinition property');
        $query->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->_as('parent')
              ->inIs('CLASS')
              ->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->_as('constant')
              ->outIs('CONSTANT')
              ->tokenIs('T_STRING')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'constant')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count1 = $result->toInt();
        display("Set $count1 parent constant definitions");

        $query = $this->newQuery('SetParentDefinition direct');
        $query->atomIs('String', Analyzer::WITHOUT_CONSTANTS)
              ->fullnspathIs('\\\\parent', Analyzer::CASE_SENSITIVE)
              ->_as('parent')
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'parent')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count2 = $result->toInt();
        
        $count = $count1 + $count2;
        display("Set $count parent definitions");
    }
}

?>
