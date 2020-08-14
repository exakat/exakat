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

class PropagateCalls extends Complete {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                     'Complete/SetClassMethodRemoteDefinition',
                    );
    }

    public function analyze(): void {
        // No need to run twice
        $this->processLocalDefinition();
        $this->propagateGlobals();
        $this->propagateTypehint();
        $this->processFluentInterfaces();

        $count = $this->propagateCalls();

        $this->setCount($count);
    }

    private function propagateCalls($level = 0): int {
        $total = 0;

        $total += $this->processReturnedType();
        $total += $this->processParenthesis();

        if ($total > 0 && $level < 15) {
            $total += $this->propagateCalls($level + 1);
        }

        return $total;
    }

    private function processLocalDefinition(): int {
        //$a = new A; $a->method()
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')  // No check on atoms :
              ->atomIs(array('Variabledefinition', 'Parametername', 'Propertydefinition', 'Globaldefinition', 'Staticdefinition', 'Virtualproperty'), self::WITHOUT_CONSTANTS)
              ->outIs('DEFAULT')
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              // Get only the first method found. Possibly OK for class, but may fail later
              ->filter(
                  $this->side()
                       ->atomIs('Class', self::WITHOUT_CONSTANTS)
                       ->goToAllParentsTraits(self::INCLUDE_SELF)
                       ->outIs('METHOD')
                       ->outIs('NAME')
                       ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
                       ->inIs('NAME')
                       ->range(0, 1)
                       ->as('origin')
                       ->dedup(array('first', 'origin'))
                       ->addETo('DEFINITION', 'first')
                )
              ->count();
        $c1 = $this->rawQuery()->toInt();

        //$a = new A; $a->property
        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs(array('Variabledefinition', 'Parametername', 'Propertydefinition', 'Globaldefinition', 'Staticdefinition', 'Virtualproperty'), self::WITHOUT_CONSTANTS)
              ->outIs('DEFAULT')
              ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', self::CASE_SENSITIVE)
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
       $c2 = $this->rawQuery()->toInt();

        //$a = function () {}; $a()
        $this->atomIs(array('Variabledefinition', 'Parametername', 'Propertydefinition', 'Globaldefinition', 'Staticdefinition', 'Virtualproperty'), self::WITHOUT_CONSTANTS)
             ->outIs('DEFAULT')
             ->atomIs(array('Closure', 'Arrowfunction'), self::WITHOUT_CONSTANTS)
             ->hasIn('RIGHT')
             ->hasNoIn('DEFINITION')
             ->as('origin')
             ->back('first')
             ->outIs('DEFINITION')
             ->inIs('NAME')
             ->atomIs('Functioncall', self::WITHOUT_CONSTANTS)
             ->as('call')
             ->dedup(array('call', 'origin'))
             ->addEFrom('DEFINITION', 'origin')
             ->count();
       $c3 = $this->rawQuery()->toInt();

       return $c1 + $c2 + $c3;
    }

    private function processReturnedType(): int {
        // function foo() : X {}; $a = foo(); $a->b();
        $this->atomIs(self::FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
              ->hasOut('RETURNTYPE')
              ->outIs('DEFINITION')
              ->atomIs(self::FUNCTIONS_CALLS, self::WITHOUT_CONSTANTS)
              ->inIs('DEFAULT')
              ->atomIs(array('Propertydefinition', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition'), self::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->back('first')

              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'method')
              ->count();
        $c1 = $this->rawQuery()->toInt();

        // function foo() : X {}; foo()->b();
        $this->atomIs(self::FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
              ->hasOut('RETURNTYPE')
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->as('method')
              ->back('first')

              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'method')
              ->count();
        $c1 += $this->rawQuery()->toInt();

        // function foo() : X {};foo()->b();
        $this->atomIs(self::FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
              ->hasOut('RETURNTYPE')
              ->outIs('DEFINITION')
              ->atomIs(self::FUNCTIONS_CALLS, self::WITHOUT_CONSTANTS)
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->as('method')
              ->back('first')

              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->as('origin')
              ->dedup(array('method', 'origin'))
              ->addETo('DEFINITION', 'method')
              ->count();
        $c2 = $this->rawQuery()->toInt();

        $this->atomIs(self::FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
             ->hasOut('RETURNTYPE')
             ->outIs('DEFINITION')
             ->atomIs(self::FUNCTIONS_CALLS, self::WITHOUT_CONSTANTS)
             ->inIs('DEFAULT')
             ->atomIs(array('Propertydefinition', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition'), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->hasNoIn('DEFINITION')
             ->as('member')
             ->atomIs('Member', self::WITHOUT_CONSTANTS)
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->back('first')

             ->outIs('RETURNTYPE')
             ->inIs('DEFINITION')
             ->atomIs('Class', self::WITHOUT_CONSTANTS)
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('PPP')
             ->outIs('PPP')
             ->samePropertyAs('propertyname', 'name', self::CASE_SENSITIVE)
              ->as('origin')
              ->dedup(array('member', 'origin'))
             ->addETo('DEFINITION', 'member')
              ->count();
        $c3 = $this->rawQuery()->toInt();

       return $c1 + $c2 + $c3;
    }

    private function processParenthesis(): int {

        // (new x)->foo()
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c1 = $this->rawQuery()->toInt();

        // (new x)::foo()
        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c2 = $this->rawQuery()->toInt();

        // (new x)::foo()
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c3 = $this->rawQuery()->toInt();

        // (new x)::foo()
        $this->atomIs('Staticproperty', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c4 = $this->rawQuery()->toInt();

        // (new x)::FOO
        $this->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c5 = $this->rawQuery()->toInt();

        return $c1 + $c2 + $c3 + $c4 + $c5;
    }

    private function propagateGlobals(): int {
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c1 = $this->rawQuery()->toInt();

        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c2 = $this->rawQuery()->toInt();

        return $c1 + $c2;
    }

    private function propagateTypehint(): int {
        $this->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c1 = $this->rawQuery()->toInt();

        $this->atomIs('Member', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c2 = $this->rawQuery()->toInt();

        $this->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c3 = $this->rawQuery()->toInt();

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
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first')
              ->count();
        $c4 = $this->rawQuery()->toInt();

        return $c1 + $c2 + $c3 + $c4;
    }

    private function processFluentInterfaces(): int {
        $this->atomIs(array('Methodcall', 'Staticmethodcall'), self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')

              ->outIs(array('OBJECT', 'CLASS'))
              ->atomIs(array('Methodcall', 'Staticmethodcall'), self::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(array('Method', 'Magicmethod'))
              ->inIs(array('METHOD', 'MAGICMETHOD'))

              ->atomIs(self::CLASSES_TRAITS, self::WITHOUT_CONSTANTS)
              ->goToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->as('origin')
              ->dedup(array('first', 'origin'))

              ->addETo('DEFINITION', 'first')
              ->count();
        $res = $this->rawQuery();

        return $res->toInt();
    }
}

?>
