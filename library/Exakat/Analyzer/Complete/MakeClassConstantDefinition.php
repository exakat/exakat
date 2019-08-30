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

namespace Exakat\Analyzer\Complete;

use Exakat\Analyzer\Analyzer;

class MakeClassConstantDefinition extends Analyzer {
    public function dependsOn() {
        return array('Complete/SetParentDefinition',
                    );
    }

    public function analyze() {
        $this->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        // Create link between Class constant and definition
        $this->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs('Static', Analyzer::WITHOUT_CONSTANTS)
              
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllChildren(Analyzer::EXCLUDE_SELF)
              ->outIs('CONST')
              ->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
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
              ->count();
        $this->rawQuery();

        $this->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
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
              ->count();
        $this->rawQuery();
    }
}

?>
