<?php declare(strict_types = 1);
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

class SetClassRemoteDefinitionWithGlobal extends Complete {
    public function analyze(): void {
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs(array('Globaldefinition', 'Variabledefinition'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs('Virtualglobal', self::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', self::WITHOUT_CONSTANTS) // code is =
              ->outIs('RIGHT')
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method');
        $this->prepareQuery();

        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
              ->as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs(array('Globaldefinition', 'Variabledefinition'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs('Virtualglobal', self::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', self::WITHOUT_CONSTANTS) // code is =
              ->outIs('RIGHT')
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member');
        $this->prepareQuery();
    }
}

?>
