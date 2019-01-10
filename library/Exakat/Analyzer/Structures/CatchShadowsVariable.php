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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class CatchShadowsVariable extends Analyzer {
    public function analyze() {
        // Catch inside a function
        $this->atomIs('Catch')
             ->hasFunction()
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'catchVariable')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'catchVariable')
             ->hasNoIn('VARIABLE')
             ->hasNoCatch()
             ->back('first');
        $this->prepareQuery();

        // Catch outside a function
        $this->atomIs('Catch')
             ->hasNoFunction(self::$FUNCTIONS_ALL)
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'catchVariable')
             ->goToFile()
             ->outIs('FILE')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'catchVariable')
             ->hasNoIn('VARIABLE')
             ->hasNoCatch()
             ->back('first');
        $this->prepareQuery();
    }
}

?>
