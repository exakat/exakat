<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class RaisedAccessLevel extends Analyzer {
    public function analyze() {
        // raised to private
        $this->atomIs('Ppp')
             ->hasOut(array('PUBLIC', 'PROTECTED'))
             ->outIs('PPP')
             ->_as('results')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasOut('PRIVATE')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->samePropertyAs('code', 'property')
             ->back('results');
        $this->prepareQuery();

        // raised to protected
        $this->atomIs('Ppp')
             ->hasOut('PUBLIC')
             ->outIs('PPP')
             ->_as('results')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasOut('PROTECTED')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->samePropertyAs('code', 'property')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
