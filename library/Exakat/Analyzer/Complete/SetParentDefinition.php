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
        $this->atomIs('Parent', self::WITHOUT_CONSTANTS)
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery(self::QUERY_NO_ANALYZED);

        $this->atomIs('Newcall', self::WITHOUT_CONSTANTS)
              ->fullnspathIs('\\parent', self::CASE_SENSITIVE)
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery(self::QUERY_NO_ANALYZED);

        $this->atomIs('Parent', self::WITHOUT_CONSTANTS)
              ->as('parent')
              ->inIs('CLASS')
              ->atomIs('Staticproperty', self::WITHOUT_CONSTANTS)
              ->as('property')
              ->outIs('MEMBER')
              ->tokenIs('T_VARIABLE')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'property');
        $this->prepareQuery(self::QUERY_NO_ANALYZED);

        $this->atomIs('Parent', self::WITHOUT_CONSTANTS)
              ->as('parent')
              ->inIs('CLASS')
              ->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
              ->as('constant')
              ->outIs('CONSTANT')
              ->tokenIs('T_STRING')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'constant');
        $this->prepareQuery(self::QUERY_NO_ANALYZED);

        $this->atomIs('String', self::WITHOUT_CONSTANTS)
              ->fullnspathIs('\\\\parent', self::CASE_SENSITIVE)
              ->as('parent')
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'parent');
        $this->prepareQuery(self::QUERY_NO_ANALYZED);
    }
}

?>
