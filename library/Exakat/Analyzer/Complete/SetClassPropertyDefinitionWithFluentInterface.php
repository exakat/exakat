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

class SetClassPropertyDefinitionWithFluentInterface extends Analyzer {
    public function analyze() {
        // $a->method1()->method2(); Link the method call to the method definition
        // $a must be a known object of a fluent class (returning $this, tough not checked yet)
        $this->atomIs(array('Methodcall', 'Staticmethodcall'), Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')

              ->outIs(array('OBJECT', 'CLASS'))
              ->atomIs(array('Methodcall', 'Staticmethodcall'), Analyzer::WITHOUT_CONSTANTS)
              ->outIsIE('OBJECT')
              // Object below must have a definition
              ->inIs('DEFINITION')

              ->atomIs(array('Class', 'Classanonymous', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')

              ->addETo('DEFINITION', 'method')
              ->count();
        $this->rawQuery();
    }
}

?>
