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

class CreateDefaultValues extends Complete {
    public function analyze() {
        // Link initial values for containers
        $this->atomIs(array('Variabledefinition',
                            'Staticdefinition',
                            'Globaldefinition',
                            'Staticdefinition',
                            'Virtualproperty',
                            'Propertydefinition',
                            'Parametername',
                            ), self::WITHOUT_CONSTANTS)
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('DEFAULT')
                             ->hasIn('RIGHT')
                     )
             )
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation', self::WITHOUT_CONSTANTS)
             ->codeIs(array('=', '??='), self::TRANSLATE, self::CASE_SENSITIVE) // can't accept .=, +=, etc.

             // doesn't use self : $a = $a + 1 is not a default value
             ->followParAs('RIGHT')
             ->not(
                $this->side()
                     ->outIs('LEFT')
                     ->atomInsideNoDefinition(self::VARIABLES_ALL)
                     ->inIs('DEFINITION')
                     ->inIsIE('NAME')
                     ->raw('is(neq("first"))')
             )

             ->addEFrom('DEFAULT', 'first');
        $this->prepareQuery();
    }
}

?>
