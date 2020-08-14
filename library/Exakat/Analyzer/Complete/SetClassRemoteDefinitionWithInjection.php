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

class SetClassRemoteDefinitionWithInjection extends Complete {
    public function analyze(): void {
        $this->atomIs(self::CLASSES_ALL, self::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('TYPEHINT')
              ->outIs('NAME')
              ->outIs('DEFINITION')
              ->atomIs('Variable', self::WITHOUT_CONSTANTS)
              ->inIs('RIGHT')
              ->atomIs('Assignation', self::WITHOUT_CONSTANTS)
              ->outIs('LEFT')
              ->atomIs('Member', self::WITHOUT_CONSTANTS)

              ->inIs('DEFINITION')
              ->atomIs('Propertydefinition',  self::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->atomIs('Member', self::WITHOUT_CONSTANTS)
              ->addEFrom('DEFINITION', 'first');
        $this->prepareQuery();
    }
}

?>
