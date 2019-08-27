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

class CreateMagicProperty extends Analyzer {
    public function dependsOn() {
        return array('Complete/OverwrittenProperties',
                    );
    }

    public function analyze() {

        // Missing : typehinted properties, return typehint, clone

        // link to __get
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isRead', true)
              ->inIs('DEFINITION')
              ->atomIs('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
              ->not(
                $this->side()
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
                     ->not(
                        $this->side()
                              ->inIs('PPP')
                              ->is('visibility', 'private')
                     )
              )
              ->back('first')
              ->outIs('OBJECT')
              ->atomIs('This', Analyzer::WITHOUT_CONSTANTS)
              ->goToClass()
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__get', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isRead', true)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('Variableobject', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('DEFAULT')
              ->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__get', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isRead', true)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('Variableobject', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__get', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        // link to __set
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isModified', true)
              ->inIs('DEFINITION')
              ->atomIs('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
              ->not(
                $this->side()
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
                     ->not(
                        $this->side()
                              ->inIs('PPP')
                              ->is('visibility', 'private')
                     )
              )
              ->back('first')
              ->outIs('OBJECT')
              ->atomIs('This', Analyzer::WITHOUT_CONSTANTS)
              ->goToClass()
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__set', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isModified', true)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('Variableobject', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('DEFAULT')
              ->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__set', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();

        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isModified', true)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('Variableobject', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__set', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->count();
        $this->rawQuery();
    }
}

?>
