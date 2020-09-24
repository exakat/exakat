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

class SetClassRemoteDefinitionWithTypehint extends Complete {
    public function analyze(): void {

        // function bar(A $a) { $a->foo()}; class A { function foo() {}}
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->atomIs('Variableobject')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter', self::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(self::STATIC_NAMES, self::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
              )
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method');
        $this->prepareQuery();

        // class B { public A $p; function bar() { $this->a->foo()}; class A { function foo() {}}
        $this->atomIs(array('Methodcall', 'Staticmethodcall'), self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs(array('OBJECT', 'CLASS'))
              ->atomIs(array('Member', 'Staticproperty'))
              ->inIs('DEFINITION')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->atomIs(array('Identifier', 'Nsname'))
              ->inIs('DEFINITION')
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method');
        $this->prepareQuery();

        // function bar(A $a) { $a->p}; class A { public $p;}
        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
              ->as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->atomIs('Variableobject')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter', self::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(self::STATIC_NAMES, self::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
              )
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member');
        $this->prepareQuery();

        // class B { public A $p; function bar() { $this->a->p2()}; class A { public $p2;}
        $this->atomIs(array('Member', 'Staticproperty'), self::WITHOUT_CONSTANTS)
              ->as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs(array('OBJECT', 'CLASS'))
              ->atomIs(array('Member', 'Staticproperty'))
              ->inIs('DEFINITION')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->atomIs(array('Identifier', 'Nsname'))
              ->inIs('DEFINITION')
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->raw('filter{ it.get().value("propertyname") == name || it.get().value("code") == name;}')
              ->addETo('DEFINITION', 'member');
        $this->prepareQuery();

        // function bar(A $a) { $a::C}; class A { const C = 1;}
        $this->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
              ->as('constante')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('CONSTANT')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter', self::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(self::STATIC_NAMES, self::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
              )
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'constante');
        $this->prepareQuery();

        // class B { public A $p; function bar() { $this->a::C}; class A { const C = 1;}
        $this->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
              ->as('constante')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('CONSTANT')
              ->outIs('CLASS')
              ->atomIs(array('Member', 'Staticproperty'))
              ->inIs('DEFINITION')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->atomIs(array('Identifier', 'Nsname'))
              ->inIs('DEFINITION')
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'constante');
        $this->prepareQuery();

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs('Variable', self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(self::STATIC_NAMES, self::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
              )
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->isNot('visibility', 'private')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();
    }
}

?>
