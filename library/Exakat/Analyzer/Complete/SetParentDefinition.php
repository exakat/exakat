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

class SetParentDefinition extends Analyzer {
    public function analyze() {
        $this->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Newcall', Analyzer::WITHOUT_CONSTANTS)
              ->fullnspathIs('\\parent', Analyzer::CASE_SENSITIVE)
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
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
              ->count();
        $this->rawQuery();

        $this->atomIs(' ', Analyzer::WITHOUT_CONSTANTS)
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
              ->count();
        $this->rawQuery();

        $this->atomIs('String', Analyzer::WITHOUT_CONSTANTS)
              ->fullnspathIs('\\\\parent', Analyzer::CASE_SENSITIVE)
              ->_as('parent')
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'parent')
              ->count();
        $this->rawQuery();
    }
}

?>
