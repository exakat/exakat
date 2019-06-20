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
        $positionsWithConstants = array();
        foreach($functions->alternative as $position => $functionsList) {
            foreach(array_keys((array) $functionsList) as $function) {
                $fqn = makeFullNsPath($function);
                
                array_collect_by($positionsWithConstants, $fqn, (int) $position);
            }
        }

        // Not a PHP constant
        $this->atomFunctionIs(array_keys($positionsWithConstants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positionsWithConstants, 'fqn')
             ->atomIs(self::$CONSTANTS_ALL)
             ->analyzerIsNot('Constants/IsPhpConstant')
             ->back('first');
        $this->prepareQuery();

       // unwanted guests
        $this->atomFunctionIs(array_keys($positionsWithConstants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positionsWithConstants, 'fqn')
             ->atomIs(array('Boolean', 'Null', 'Integer', 'Float', 'String', 'Concatenation', 'Logical'))
             ->back('first');
        $this->prepareQuery();

        foreach($functions->alternative as $position => $functionsList) {
            $constantsWithPosition = array();
            foreach($functionsList as $function => $constants) {
                $fqn = makeFullNsPath($function);

                $constantsWithPosition[$fqn] = makeFullNsPath($constants, \FNP_CONSTANT);
            }

            $this->atomFunctionIs(array_keys($constantsWithPosition))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs(self::$CONSTANTS_ALL)
                 ->analyzerIs('Constants/IsPhpConstant')
                 ->isNotHash('fullnspath', $constantsWithPosition, 'fqn')
                 ->back('first');
            $this->prepareQuery();
        }

        /////////////////////////////////////////////////////////////////////////////
        // combinaison : several constants may be combined with a logical operator
        $positionsWithConstants = array();
        foreach($functions->combinaison as $position => $functionsList) {
            foreach((array) $functionsList as $function => $constants) {
                $fqn = makeFullNsPath($function);
                
                array_collect_by($positionsWithConstants, $fqn, (int) $position);
            }
        }

        // Not a PHP constant
        $this->atomFunctionIs(array_keys($positionsWithConstants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positionsWithConstants, 'fqn')
             ->atomIs(self::$CONSTANTS_ALL)
             ->analyzerIsNot('Constants/IsPhpConstant')
             ->back('first');
        $this->prepareQuery();

        // in a logical combinaison, check that constants are at least PHP's one
        $this->atomFunctionIs(array_keys($positionsWithConstants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positionsWithConstants, 'fqn')
             ->atomIs('Logical')
             ->atomInsideNoDefinition(self::$CONSTANTS_ALL)
             ->analyzerIsNot('Constants/IsPhpConstant')
             ->back('first');
        $this->prepareQuery();

       // unwanted guests
       $this->atomFunctionIs(array_keys($positionsWithConstants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positionsWithConstants, 'fqn')
             ->atomIs(array('Boolean', 'Null', 'Float'))
             ->back('first');
       $this->prepareQuery();

       $this->atomFunctionIs(array_keys($positionsWithConstants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('ARGUMENT')
             ->isHash('rank', $positionsWithConstants, 'fqn')
             ->atomIs('Integer')
             ->codeIsNot(array('0', '-1'))
             ->back('first');
       $this->prepareQuery();

        // combinaison : several constants may be combined with a logical operator
        foreach($functions->combinaison as $position => $functionsList) {

            $constantsWithPosition = array();
            foreach($functionsList as $function => $constants) {
                $fqn = makeFullNsPath($function);

                $constantsWithPosition[$fqn] = makeFullNsPath($constants, \FNP_CONSTANT);
            }

            $this->atomFunctionIs(array_keys($constantsWithPosition))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomInsideNoDefinition(self::$CONSTANTS_ALL)
                 ->analyzerIs('Constants/IsPhpConstant')
                 ->isNotHash('fullnspath', $constantsWithPosition, 'fqn')
                 ->back('first');
            $this->prepareQuery();

            // in a logical combinaison, check that constants are the one for the function
            $this->atomFunctionIs(array_keys($constantsWithPosition))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('Logical')
                 ->atomInsideNoDefinition(self::$CONSTANTS_ALL)
                 ->analyzerIs('Constants/IsPhpConstant')
                 ->isNotHash('fullnspath', $constantsWithPosition, 'fqn')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
