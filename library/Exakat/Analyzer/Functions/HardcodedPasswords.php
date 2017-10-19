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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class HardcodedPasswords extends Analyzer {
    public function analyze() {
        // Position is 0 based
        $passwordsFunctions = $this->loadJson('php_logins.json');

        $functions = (array) $passwordsFunctions->functions;

        $positions = array();
        foreach($functions as $function => $position) {
            if (isset($positions[$position])) {
                $positions[$position][] = '\\'.$function;
            } else {
                $positions[$position] = array('\\'.$function);
            }
        }

        foreach($positions as $position => $function) {
            $this->atomFunctionIs($function)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('String')
                 ->back('first');
            $this->prepareQuery();
        }
        
        // ['password' => 1];
        $this->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIs('password')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('String')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
