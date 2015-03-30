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
        $functions = $this->loadIni('php_constant_arguments.ini');

        // combinaison : several constants may be combined.
        $positions = array(0, 1, 2, 3, 4, 5);
        foreach($positions as $position) {
            if (!isset($functions["combinaison$position"])) { continue; }

            foreach($functions["combinaison$position"] as $function => $constants) {
                $constants = $this->makeFullNsPath(explode(',', $constants));

                // we don't like anything else than a legit constant
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIsNot(array('Identifier', 'Logical', 'Variable', 'Property', 'Functioncall',
                                       'Methodcall', 'Staticproperty', 'Staticmethodcall', 'Array'))
                     ->back('first');
                $this->prepareQuery();

                // if it's a constant, check that constant
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs('Identifier')
                     ->fullnspathIsNot($constants)
                     ->back('first');
                $this->prepareQuery();

                // in a logical combinaison, check that constants are OK
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs('Logical')
                     ->atomInside('Identifier')
                     ->hasNoIn('SUBNAME')
                     ->fullnspathIsNot($constants)
                     ->back('first');
                $this->prepareQuery();
            }
        }

        $positions = array(0, 1, 2, 3, 4, 5);
        foreach($positions as $position) {
            if (!isset($functions["alternative$position"])) { continue; }

            foreach($functions["alternative$position"] as $function => $constants) {
                $constants = $this->makeFullNsPath(explode(',', $constants));

                // we don't like anything else than a legit constant
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIsNot(array('Identifier', 'Nsname', 'Variable', 'Property', 'Functioncall',
                                       'Methodcall', 'Staticproperty', 'Staticmethodcall', 'Array'))
                     ->back('first');
                $this->prepareQuery();

                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(array('Identifier', 'Nsname'))
                     ->fullnspathIsNot($constants)
                     ->back('first');
                $this->prepareQuery();
            }
        }


    }
}

?>
