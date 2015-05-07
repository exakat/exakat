<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Functions;

use Analyzer;

class UseConstantAsArguments extends Analyzer\Analyzer {
    public function analyze() {
        $functions = $this->loadJson('php_constant_arguments.json');

        // combinaison : several constants may be combined.
        $positions = range(0, count((array) $functions->combinaison) - 1);
        foreach($positions as $position) {
            foreach($functions->combinaison->{$position} as $function => $constants) {
                $constants = $this->makeFullNsPath($constants);

                // if it's a constant, check that constant
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(array('Identifier', 'Nsname'))
                     ->fullnspathIsNot($constants)
                     ->back('first');
                $this->prepareQuery();

                // in a logical combinaison, check that constants are OK
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs('Logical')
                     ->atomInside(array('Identifier', 'Nsname'))
                     ->hasNoIn('SUBNAME')
                     ->fullnspathIsNot($constants)
                     ->back('first');
                $this->prepareQuery();

                // unwanted guests
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(array('Boolean', 'Null', 'Integer', 'Float'))
                     ->back('first');
                $this->prepareQuery();
            }
        }

        $positions = range(0, count((array) $functions->alternative) - 1);
        foreach($positions as $position) {
            foreach($functions->alternative->{$position} as $function => $constants) {
                $constants = $this->makeFullNsPath($constants);

                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(array('Identifier', 'Nsname'))
                     ->fullnspathIsNot($constants)
                     ->back('first');
                $this->prepareQuery();

                // unwanted guests
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(array('Boolean', 'Null', 'Integer', 'Float', 'Logical'))
                     ->back('first');
                $this->prepareQuery();
            }
        }
    }
}

?>
