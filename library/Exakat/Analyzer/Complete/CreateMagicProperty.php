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
                     'Complete/SetClassRemoteDefinitionWithTypehint',
                    );
    }

    public function analyze() {

        // Missing : typehinted properties, return typehint, clone

        // link to __get
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
             ->is('isRead', true)
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Propertydefinition')
             )
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition')
             )
             ->outIs('OBJECT')
             ->atomIs(array('Variableobject', 'This'), Analyzer::WITHOUT_CONSTANTS)
             ->inIs('DEFINITION') // Good enough for This
             ->optional(          // For arguments
                $this->side()
                     ->inIs('NAME')
                     ->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
                     ->outIs('TYPEHINT')
                     ->inIs('DEFINITION')
                     ->prepareSide()
             )

            // In case we are in an interface
             ->optional(
                $this->side()
                     ->atomIs('Interface', Analyzer::WITHOUT_CONSTANTS)
                     ->outIs('DEFINITION')
                     ->inIs('IMPLEMENTS')
                     ->prepareSide()
             )

             ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
             ->outIs('MAGICMETHOD')
             ->outIs('NAME')
             ->codeIs('__get', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
             ->inIs('NAME')

             ->addETo('DEFINITION', 'first')
             ->back('first');
        $this->prepareQuery();

        // link to __set
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
             ->is('isModified', true)
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Propertydefinition')
             )
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition')
             )
             ->outIs('OBJECT')
             ->atomIs(array('Variableobject', 'This'), Analyzer::WITHOUT_CONSTANTS)
             ->inIs('DEFINITION') // Good enough for This
             ->optional(          // For arguments
                $this->side()
                     ->inIs('NAME')
                     ->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
                     ->outIs('TYPEHINT')
                     ->inIs('DEFINITION')
                     ->prepareSide()
             )

            // In case we are in an interface
             ->optional(
                $this->side()
                     ->atomIs('Interface', Analyzer::WITHOUT_CONSTANTS)
                     ->outIs('DEFINITION')
                     ->inIs('IMPLEMENTS')
                     ->prepareSide()
             )

             ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
             ->outIs('MAGICMETHOD')
             ->outIs('NAME')
             ->codeIs('__set', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->addETo('DEFINITION', 'first')

             ->back('first');
        $this->prepareQuery();

        // isset($this->a)
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Propertydefinition')
             )
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition')
             )
             ->inIs('ARGUMENT')
             ->atomIs('Isset')
             ->back('first')

             ->outIs('OBJECT')
             ->atomIs(array('Variableobject', 'This'), Analyzer::WITHOUT_CONSTANTS)
             ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->inIs('NAME')
                     ->outIs('TYPEHINT')
                     ->prepareSide()
             )
             ->inIs('DEFINITION')
             ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
             ->outIs('MAGICMETHOD')
             ->outIs('NAME')
             ->codeIs('__isset', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->addETo('DEFINITION', 'first')
             ->back('first');
        $this->prepareQuery();

        // unset($this->a)
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Propertydefinition')
             )
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition')
             )
             ->inIs('ARGUMENT')
             ->atomIs('Unset')
             ->back('first')

             ->outIs('OBJECT')
             ->atomIs(array('Variableobject', 'This'), Analyzer::WITHOUT_CONSTANTS)
             ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->inIs('NAME')
                     ->outIs('TYPEHINT')
                     ->prepareSide()
             )              ->inIs('DEFINITION')
             ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
             ->outIs('MAGICMETHOD')
             ->outIs('NAME')
             ->codeIs('__unset', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->addETo('DEFINITION', 'first')
             ->back('first');
        $this->prepareQuery();

        // unset() $this->a
        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs('Propertydefinition')
             )
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('OVERWRITE')
                     ->atomIs('Propertydefinition')
             )
             ->inIs('CAST')
             ->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->back('first')

              ->outIs('OBJECT')
             ->atomIs(array('Variableobject', 'This'), Analyzer::WITHOUT_CONSTANTS)
             ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->inIs('NAME')
                     ->outIs('TYPEHINT')
                     ->prepareSide()
             )
             ->inIs('DEFINITION')
             ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
             ->outIs('MAGICMETHOD')
             ->outIs('NAME')
             ->codeIs('__unset', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->addETo('DEFINITION', 'first')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
