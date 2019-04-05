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

class MakeClassConstantDefinition extends LoadFinal {
    public function run() {

        // Create link between Class constant and definition
        $query = $this->newQuery('MakeClassConstantDefinition direct');
        $query->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CONST')
              ->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countD = $result->toInt();

        $query = $this->newQuery('MakeClassConstantDefinition parents');
        $query->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static', 'Parent'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->outIs('CONST')
              ->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->isNot('visibility', 'private')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countI = $result->toInt();

        $query = $this->newQuery('MakeClassConstantDefinition Parent only');
        $query->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->isNot('visibility', 'private')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countI = $result->toInt();
        $count = $countD + $countI;
        
        display("Create $count link between Class constant and definition");
    }
}

?>
