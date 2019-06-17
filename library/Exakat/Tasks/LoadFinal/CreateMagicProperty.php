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


namespace Exakat\Tasks\LoadFinal;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\Query;

class CreateMagicProperty extends LoadFinal {
    public function run() {
        // Missing : typehinted properties, return typehint, clone

        // link to __get
        $query = $this->newQuery('CreateMagicProperty this');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isRead', true)
              ->inIs('DEFINITION')
              ->atomIs('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
              ->not(
                $query->side()
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
                     ->not(
                        $query->side()
                              ->inIs('PPP')
                              ->is('visibility', 'private')
                              ->prepareSide()
                     )
                     ->prepareSide()
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
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countThis = $result->toInt();

        $query = $this->newQuery('CreateMagicProperty new');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
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
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countNew = $result->toInt();

        $query = $this->newQuery('CreateMagicProperty typehint');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
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
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countTypehint = $result->toInt();

        $count = $countThis + $countNew + $countTypehint;
        display("Created $count definitions to magic methods __get");

        // link to __set
        $query = $this->newQuery('CreateMagicProperty this');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->is('isModified', true)
              ->inIs('DEFINITION')
              ->atomIs('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
              ->not(
                $query->side()
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
                     ->not(
                        $query->side()
                              ->inIs('PPP')
                              ->is('visibility', 'private')
                              ->prepareSide()
                     )
                     ->prepareSide()
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
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countThis = $result->toInt();

        $query = $this->newQuery('CreateMagicProperty new');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
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
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countNew = $result->toInt();

        $query = $this->newQuery('CreateMagicProperty typehint');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
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
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countTypehint = $result->toInt();

        $count = $countThis + $countNew + $countTypehint;
        display("Created $count definitions to magic methods __set");
    }
}

?>
