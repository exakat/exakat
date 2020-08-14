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

class SetParentDefinition extends Complete {
    public function analyze(): void {
        //parent:: -> class -> extends
        $this->atomIs('Parent', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->goToClass()
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
             ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        //new parent -> class -> extends
        $this->atomIs('Newcall', self::WITHOUT_CONSTANTS)
             ->analyzerIsNot('self')
             ->hasNoIn('DEFINITION')
             ->fullnspathIs('\\parent', self::CASE_SENSITIVE)
             ->goToClass()
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
             ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        //parent::$property
        $this->atomIs('Parent', self::WITHOUT_CONSTANTS)
             ->as('parent')
             ->inIs('CLASS')
             ->atomIs('Staticproperty', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
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
              ->as('origin')
              ->dedup(array('property', 'origin'))
             ->addETo('DEFINITION', 'property');
        $this->prepareQuery();

        // parent::constant
        $this->atomIs('Parent', self::WITHOUT_CONSTANTS)
             ->as('parent')
             ->inIs('CLASS')
             ->atomIs('Staticconstant', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
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
              ->as('origin')
              ->dedup(array('constant', 'origin'))
             ->addETo('DEFINITION', 'constant');
        $this->prepareQuery();

        $this->atomIs('String', self::WITHOUT_CONSTANTS)
             ->fullnspathIs('\\\\parent', self::CASE_SENSITIVE)
             ->hasNoIn('DEFINITION')
             ->as('parent')
             ->goToClass()
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
              ->as('origin')
              ->dedup(array('parent', 'origin'))
             ->addETo('DEFINITION', 'parent');
        $this->prepareQuery();
    }
}

?>
