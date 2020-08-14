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


class OverwrittenConstants extends Complete {
    public function analyze(): void {
        // class x { protected const X = 1;}
        // class xx extends x {  protected const X = 1;}
        $this->atomIs('Constant', self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('code', 'name')
              ->goToClass()
              ->goToAllImplements(self::EXCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->atomIs('Constant', self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->samePropertyAs('code', 'name',  self::CASE_SENSITIVE)
              ->inIs('NAME')
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();
    }
}

?>
