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

class PropertyUsedBelow extends Analyzer {
    public function analyze() {
        //////////////////////////////////////////////////////////////////
        // property + $this->property
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('PPP')
             ->isNot('static', true)
             ->outIs('PPP')
             ->_as('ppp')
             ->outIs('DEFINITION')
             ->atomIs('Member')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('ppp');
        $this->prepareQuery();

        //////////////////////////////////////////////////////////////////
        // static property : inside the self class
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('PPP')
             ->is('static', true)
             ->outIs('PPP')
             ->_as('ppp')
             ->outIs('DEFINITION')
             ->atomIs('Staticproperty')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('ppp');
        $this->prepareQuery();
        // This could be also checking for fnp : it needs to be a 'family' class check.
    }
}

?>
