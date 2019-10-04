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

class SetClassRemoteDefinitionWithTypehint extends Analyzer {
    public function analyze() {

        $this->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(array('Nsname', 'Identifier'), Analyzer::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
                       ->prepareSide(),
                        array()
              )
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->back('first');
        $this->prepareQuery();

        $this->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->atomIs('Variableobject')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(array('Nsname', 'Identifier'), Analyzer::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
                       ->prepareSide(),
                        array()
              )
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member')
              ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->_as('constante')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('CONSTANT')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(array('Nsname', 'Identifier'), Analyzer::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
                       ->prepareSide(),
                        array()
              )              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'constante')
              ->back('first');
        $this->prepareQuery();

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $this->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs('Variable', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->optional(
                  $this->side()
                       ->atomIs('Interface')
                       ->outIs('DEFINITION')
                       ->atomIs(array('Nsname', 'Identifier'), Analyzer::WITHOUT_CONSTANTS)
                       ->inIs('IMPLEMENTS')
                       ->prepareSide(),
                        array()
              )
              // No check on Atom == Class, as it may not exists
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->isNot('visibility', 'private')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->back('first');
        $this->prepareQuery();
    }
}

?>
