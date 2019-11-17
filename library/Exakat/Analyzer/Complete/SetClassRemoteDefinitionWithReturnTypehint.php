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

class SetClassRemoteDefinitionWithReturnTypehint extends Analyzer {
    public function analyze() {
        $this->atomIs(self::$FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
              ->hasOut('RETURNTYPE')
              ->outIs('DEFINITION')
              ->atomIs(self::$FUNCTIONS_CALLS, self::WITHOUT_CONSTANTS)
              ->inIs('DEFAULT')
              ->atomIs(array('Propertydefinition', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition'), self::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', self::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->atomIs('Methodcall', self::WITHOUT_CONSTANTS)
              ->_as('method')
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
              ->addETo('DEFINITION', 'method')
              ->count();
        $this->rawQuery();

        $this->atomIs(self::$FUNCTIONS_ALL, self::WITHOUT_CONSTANTS)
             ->hasOut('RETURNTYPE')
             ->outIs('DEFINITION')
             ->atomIs(self::$FUNCTIONS_CALLS, self::WITHOUT_CONSTANTS)
             ->inIs('DEFAULT')
             ->atomIs(array('Propertydefinition', 'Variabledefinition', 'Globaldefinition', 'Staticdefinition'), self::WITHOUT_CONSTANTS)
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->hasNoIn('DEFINITION')
             ->_as('member')
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
             ->addETo('DEFINITION', 'member')
             ->count();
        $this->rawQuery();
    }
}

?>
