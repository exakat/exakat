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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class RaisedAccessLevel extends Analyzer {
    public function analyze() {
        // raised to private
        $this->atomIs('Ppp')
             ->is('visibility', array('public', 'protected'))
             ->outIs('PPP')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();

        // raised to protected
        $this->atomIs('Ppp')
             ->is('visibility', 'public')
             ->outIs('PPP')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'protected')
             ->outIs('PPP')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();

        // raised to private method
        $this->atomIs('Method')
             ->isNot('visibility', 'private')
             ->_as('results')
             ->outIsIE('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('METHOD')
             ->atomIs('Method')
             ->is('visibility', 'private')
             ->outIs('NAME')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();

        // raised to protected method
        $this->atomIs('Method')
             ->isNot('visibility', array('private', 'protected'))
             ->_as('results')
             ->outIsIE('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('METHOD')
             ->atomIs('Method')
             ->is('visibility', 'protected')
             ->outIs('NAME')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();

        // raised to protected or private for const
        $this->atomIs('Const')
             ->isNot('visibility', array('private', 'protected')) // Public or None
             ->outIs('CONST')
             ->_as('results')
             ->outIsIE('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('CONST')
             ->atomIs('Const')
             ->is('visibility', array('private', 'protected'))
             ->outIs('CONST')
             ->outIsIE('NAME')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->inIs('NAME');
        $this->prepareQuery();

        // raised to protected for const
        $this->atomIs('Const')
             ->is('visibility', 'protected')
             ->outIs('CONST')
             ->_as('results')
             ->outIs('NAME')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->goToAllChildren(self::EXCLUDE_SELF)
             ->outIs('CONST')
             ->atomIs('Const')
             ->is('visibility', 'private')
             ->outIs('CONST')
             ->outIs('NAME')
             ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
             ->inIs('NAME');
        $this->prepareQuery();
    }
}

?>
