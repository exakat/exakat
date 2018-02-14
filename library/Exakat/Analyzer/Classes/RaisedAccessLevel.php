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
             ->outIs('PPP')
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
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->hasOut('PROTECTED')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->samePropertyAs('code', 'property')
             ->back('results');
        $this->prepareQuery();

        // raised to private method
        $this->atomIs('Method')
             ->hasNoOut('PRIVATE')
             ->_as('results')
             ->outIsIE('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('METHOD')
             ->atomIs('Method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->samePropertyAs('code', 'property')
             ->back('results');
        $this->prepareQuery();

        // raised to protected method
        $this->atomIs('Method')
             ->hasNoOut(array('PRIVATE', 'PROTECTED'))
             ->_as('results')
             ->outIsIE('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('METHOD')
             ->atomIs('Method')
             ->hasOut('PROTECTED')
             ->outIs('NAME')
             ->samePropertyAs('code', 'property')
             ->back('results');
        $this->prepareQuery();

        // raised to protected or private for const
        $this->atomIs('Const')
             ->hasNoOut(array('PRIVATE', 'PROTECTED')) // Public or None
             ->outIs('CONST')
             ->_as('results')
             ->outIsIE('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('CONST')
             ->atomIs('Const')
             ->hasOut(array('PROTECTED', 'PRIVATE'))
             ->outIs('CONST')
             ->outIsIE('NAME')
             ->samePropertyAs('code', 'property')
             ->inIs('NAME');
        $this->prepareQuery();

        // raised to protected for const
        $this->atomIs('Const')
             ->hasOut('PROTECTED')
             ->outIs('CONST')
             ->_as('results')
             ->outIs('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('CONST')
             ->atomIs('Const')
             ->hasOut('PRIVATE')
             ->outIs('CONST')
             ->outIs('NAME')
             ->samePropertyAs('code', 'property')
             ->inIs('NAME');
        $this->prepareQuery();
    }
}

?>
