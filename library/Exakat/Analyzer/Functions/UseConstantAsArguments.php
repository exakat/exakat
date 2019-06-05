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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class UseConstantAsArguments extends Analyzer {
    public function dependsOn() {
        return array('Constants/IsPhpConstant',
                    );
    }
    
    public function analyze() {
        $functions = $this->loadJson('php_constant_arguments.json');

        //alternative : one of the constants or nothing
        $positions = range(0, count((array) $functions->alternative) - 1);
        foreach($positions as $position) {
            $fullnspath = array();
            foreach($functions->alternative->{$position} as $function => $constants) {
                $fullnspath[] = $function;
            }
            $fullnspath = makeFullNsPath($fullnspath);

            // Not a PHP constant
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs(self::$CONSTANTS_ALL)
                 ->analyzerIsNot('Constants/IsPhpConstant')
                 ->back('first');
            $this->prepareQuery();

            // unwanted guests
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs(array('Boolean', 'Null', 'Integer', 'Float', 'String', 'Concatenation', 'Logical'))
                 ->back('first');
            $this->prepareQuery();

            foreach($functions->alternative->{$position} as $function => $constants) {
                $function = makeFullNsPath($function);
                // PHP constant but wrong one
                $regex = '(' . implode('|', $constants) . ')\$';
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(self::$CONSTANTS_ALL)
                     ->analyzerIs('Constants/IsPhpConstant')
                     ->regexIsNot('fullnspath', $regex)
                     ->back('first');
                $this->prepareQuery();
            }
        }

        // combinaison : several constants may be combined with a logical operator
        $positions = range(0, count((array) $functions->combinaison) - 1);
        // First loop federate some queries
        foreach($positions as $position) {
            $fullnspath    = array();
            $constantNames = array();
            foreach($functions->combinaison->{$position} as $function => $constants) {
                $fullnspath[]    = $function;
                $constantNames[] = $constants;
            }
            $constantNames = array_merge(...$constantNames);
            $fullnspath = makeFullNsPath($fullnspath);
            
            // if it's a constant, but not a PHP one
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs(self::$CONSTANTS_ALL)
                 ->analyzerIsNot('Constants/IsPhpConstant')
                 ->back('first');
            $this->prepareQuery();

            // in a logical combinaison, check that constants are at least PHP's one
            $this->atomFunctionIs($fullnspath)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('Logical')
                 ->atomInsideNoDefinition(self::$CONSTANTS_ALL)
                 ->analyzerIsNot('Constants/IsPhpConstant')
                 ->hasNoIn('NAME')
                 ->back('first');
            $this->prepareQuery();

           // unwanted guests
           $this->atomFunctionIs($fullnspath)
                ->outIs('ARGUMENT')
                ->is('rank', $position)
                ->atomIs(array('Boolean', 'Null', 'Integer', 'Float'))
                ->codeIsNot($constantNames)
                ->back('first');
           $this->prepareQuery();
        }

        // Those must be run function by function
        foreach($positions as $position) {
            foreach($functions->combinaison->{$position} as $function => $constants) {

                $function = makeFullNsPath($function);
                // if it's a PHP constant, but not a good one for the function
                $regex = '(' . implode('|', $constants) . ')\$';
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs(self::$CONSTANTS_ALL)
                     ->analyzerIs('Constants/IsPhpConstant')
                     ->regexIsNot('fullnspath', $regex)
                     ->back('first');
                $this->prepareQuery();

                // in a logical combinaison, check that constants are the one for the function
                $this->atomFunctionIs($function)
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)
                     ->atomIs('Logical')
                     ->atomInsideNoDefinition(self::$CONSTANTS_ALL)
                     ->analyzerIs('Constants/IsPhpConstant')
                     ->regexIsNot('fullnspath', $regex)
                     ->back('first');
                $this->prepareQuery();
            }
        }
    }
}

?>
