<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class LocallyUsedProperty extends Analyzer {
    public function analyze() {
        // Using 'DEFINITION' ? 

        // normal property
        // static property in an variable static::$c
        $this->atomIs(array('Class', 'Trait'))
             ->savePropertyAs('id', 'citId')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->_as('ppp')
             ->outIs('DEFINITION')
             ->goToInstruction(array('Class', 'Trait'))
             ->samePropertyAs('id', 'citId')
             ->back('ppp');
        $this->prepareQuery();
    }
}

?>
