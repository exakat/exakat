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

class SetClassMethodRemoteDefinition extends Complete {
    public function dependsOn(): array {
        return array('Complete/SetParentDefinition',
                    );
    }

    public function analyze(): void {
        // class x { function foo() {}} x::foo();
        $this->atomIs(array('Staticmethodcall', 'Methodcall'), self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs(array('CLASS', 'OBJECT'))
              // Handles variables as object
              ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFAULT')
                     ->outIs('NEW')
              )
              ->inIs('DEFINITION')
              ->atomIs(self::CLASSES_TRAITS, self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // class x { use t} trait t {function foo() {}} x::foo();
        $this->atomIs('Staticmethod', self::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs(array('Identifier', 'Nsname'), self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Trait', self::WITHOUT_CONSTANTS)
              ->goToAllParents(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first');
        $this->prepareQuery();
/*
        $this->atomIs('Staticmethodcall', self::WITHOUT_CONSTANTS)
              ->as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->has('fullnspath')
              ->savePropertyAs('fullnspath', 'fnp')
              ->filter(
                    $this->side()
                         ->goToClass()
                         ->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
                         ->goToAllParents(self::INCLUDE_SELF)
                         ->samePropertyAs('fullnspath', 'fnp', self::CASE_SENSITIVE)
              )
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(self::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', self::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method');
        $this->prepareQuery();
        */
    }
}

?>
