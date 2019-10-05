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

class SetClassMethodRemoteDefinition extends Analyzer {
    public function analyze() {
        $this->atomIs(array('Staticmethodcall', 'Methodcall'), Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs(array('CLASS', 'OBJECT'))
              // Handles variables as object
              ->optional(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFAULT')
                     ->outIs('NEW')
                     ->prepareSide()
              )
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticmethod', Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs(array('Identifier', 'Nsname'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->has('fullnspath')
              ->savePropertyAs('fullnspath', 'fnp')
              ->filter(
                    $this->side()
                         ->goToClass()
                         ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
                         ->goToAllParents(Analyzer::INCLUDE_SELF)
                         ->samePropertyAs('fullnspath', 'fnp', Analyzer::CASE_SENSITIVE)
              )
              ->inIs('DEFINITION')
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->back('first');
        $this->prepareQuery();
    }
}

?>
