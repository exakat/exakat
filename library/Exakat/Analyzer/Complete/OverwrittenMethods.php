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

class OverwrittenMethods extends Complete {
    public function analyze(): void {

        // This query is more specific than the next
        // class x {use t { t::a as b}}
        $this->atomIs('Virtualmethod', self::WITHOUT_CONSTANTS)
             ->hasNoOut('OVERWRITE')
             ->savePropertyAs('lccode', 'vname')
             ->goToClass()
             ->as('class')

             ->outIs('USE')
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('As')
             ->outIs('AS')
             ->samePropertyAs('code', 'vname',  self::CASE_INSENSITIVE)
             ->inIs('AS')
             ->outIs('NAME')
             ->outIs('METHOD')
             ->savePropertyAs('lccode', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->inIs('DEFINITION')
             ->atomIs('Trait')

             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS) // No virtualmethod here
             ->as('origin')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name',  self::CASE_INSENSITIVE)
             ->back('origin')
             ->dedup(array('first', 'origin'))
             ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();

        // class x {use t { a as b}}
        $this->atomIs('Virtualmethod', self::WITHOUT_CONSTANTS)
             ->hasNoOut('OVERWRITE')
             ->savePropertyAs('lccode', 'vname')
             ->goToClass()
             ->as('class')

             ->outIs('USE')
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('As')
             ->outIs('AS')
             ->samePropertyAs('code', 'vname',  self::CASE_INSENSITIVE)
             ->inIs('AS')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->back('class')

             ->goToAllParentsTraits(self::EXCLUDE_SELF)
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS) // No virtualmethod here
             ->as('origin')
             ->outIs('NAME')
             ->hasNoOut('METHOD')
             ->samePropertyAs('code', 'name',  self::CASE_INSENSITIVE)
             ->back('origin')
             ->dedup(array('first', 'origin'))
             ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();

        // class x {use t}
        $this->atomIs('Virtualmethod', self::WITHOUT_CONSTANTS)
             ->hasNoOut('OVERWRITE')
             ->savePropertyAs('lccode', 'name')
             ->goToClass()
             ->as('class')

             ->outIs('USE')
             ->hasNoOut('BLOCK')
             ->back('class')

             ->goToAllParentsTraits(self::EXCLUDE_SELF)
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS) // No virtualmethod here
             ->as('origin')
             ->outIs('NAME')
             ->hasNoOut('METHOD')
             ->samePropertyAs('code', 'name',  self::CASE_INSENSITIVE)
             ->back('origin')
             ->dedup(array('first', 'origin'))
             ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();

        // This must be second, so it will skip more specific configuration above
        // class x { protected function foo()  {}}
        // class xx extends x { protected function foo()  {}}
        $this->atomIs(array('Method', 'Magicmethod', 'Virtualmethod'), self::WITHOUT_CONSTANTS)
             ->hasNoOut('OVERWRITE')
             ->outIsIE('NAME')
             ->savePropertyAs('lccode', 'name')
             ->goToClass()
             ->as('theClass')
             ->goToAllParents(self::INCLUDE_SELF)
             ->raw('where(neq("theClass"))')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS) // No virtualmethod here
             ->as('origin')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name',  self::CASE_INSENSITIVE)
             ->back('origin')
             ->dedup(array('first', 'origin'))
             ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();

        // interface x { protected function foo()  {}}
        // interface xx extends x { protected function foo()  {}}
        $this->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS)
             ->hasNoOut('OVERWRITE')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->goToInterface()
             ->goToAllImplements(self::EXCLUDE_SELF)
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('NAME')
             ->samePropertyAs('code', 'name',  self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->as('origin')
             ->dedup(array('first', 'origin'))
             ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();

        // relay virtualmethods definitions to the methodcalls
        $this->atomIs(array('Method', 'Magicmethod'), self::WITHOUT_CONSTANTS)
             ->inIs('OVERWRITE')
             ->atomIs('Virtualmethod')
             ->outIs('DEFINITION')
             ->as('origin')
             ->dedup(array('first', 'origin'))
             ->addEFrom('DEFINITION', 'first');
        $this->prepareQuery();
    }
}

?>
