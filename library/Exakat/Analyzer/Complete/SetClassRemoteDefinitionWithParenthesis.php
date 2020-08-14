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

class SetClassRemoteDefinitionWithParenthesis extends Complete {
    public function analyze(): void {
        // (new x)->foo()
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->outIs('OBJECT')
              ->atomIs('Parenthesis', self::WITHOUT_CONSTANTS)
              ->outIs('CODE')
              ->optional(
                $this->side()
                     ->atomIs('Assignation')
                     ->outIs('RIGHT')
              )
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')  // No check on atoms :
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method');
        $this->prepareQuery();

        // (new x)::foo()
        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
              ->as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('OBJECT')
              ->atomIs('Parenthesis', self::WITHOUT_CONSTANTS)
              ->outIs('CODE')
              ->optional(
                $this->side()
                     ->atomIs('Assignation')
                     ->outIs('RIGHT')
              )
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')  // No check on atoms :
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member');
        $this->prepareQuery();

        // (new x)::foo()
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs('Parenthesis', self::WITHOUT_CONSTANTS)
              ->outIs('CODE')
              ->optional(
                $this->side()
                     ->atomIs('Assignation')
                     ->outIs('RIGHT')
              )
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')  // No check on atoms :
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method');
        $this->prepareQuery();

        // (new x)::foo()
        $this->atomIs('Staticproperty', self::WITHOUT_CONSTANTS)
              ->as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs('Parenthesis', self::WITHOUT_CONSTANTS)
              ->outIs('CODE')
              ->optional(
                $this->side()
                     ->atomIs('Assignation')
                     ->outIs('RIGHT')
              )
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')  // No check on atoms :
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member');
        $this->prepareQuery();

        // (new x)::FOO
        $this->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
              ->as('constant')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs('Parenthesis', self::WITHOUT_CONSTANTS)
              ->outIs('CODE')
              ->optional(
                $this->side()
                     ->atomIs('Assignation')
                     ->outIs('RIGHT')
              )
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')  // No check on atoms :
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'constant');
        $this->prepareQuery();
    }
}

?>
