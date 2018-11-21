<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class CantInstantiateClass extends Analyzer {
    public function analyze() {
        // new X();
        // class x { private function __construct() {}}
        $this->atomIs('New')
             ->outIs('NEW')
             ->inIs('DEFINITION')
             ->outIs('MAGICMETHOD')
             ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->is('visibility', array('private', 'protected'))
             ->back('first');
        $this->prepareQuery();

        // in the parent
        $this->atomIs('New')
             ->outIs('NEW')
             ->inIs('DEFINITION')
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
             ->outIs('MAGICMETHOD')
             ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->is('visibility', array('private', 'protected'))
             ->back('first');
        $this->prepareQuery();

        // in the trait
        $this->atomIs('New')
             ->outIs('NEW')
             ->inIs('DEFINITION')
             ->outIs('USE')
             ->outIs('USE')
             ->inIs('DEFINITION')
             ->outIs('MAGICMETHOD')
             ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->is('visibility', array('private', 'protected'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
