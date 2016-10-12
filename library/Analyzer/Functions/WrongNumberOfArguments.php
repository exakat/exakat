<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Data\Methods;

class WrongNumberOfArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Functions/VariableArguments');
    }
    
    public function analyze() {
        // this is for functions defined within PHP
        $data = new Methods();
        
        $functions = $data->getFunctionsArgsInterval();
        $argsMins = array();
        $argsMaxs = array();

        foreach($functions as $function) {
            if ($function['args_min'] > 0) {
                $argsMins[$function['args_min']][] = '\\'.$function['name'];
            }
            $argsMaxs[$function['args_max']][] = '\\'.$function['name'];
        }

        foreach($argsMins as $nb => $f) {
            $this->atomFunctionIs($f)
                 ->outIs('ARGUMENTS')
                 ->isLess('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }

        foreach($argsMaxs as $nb => $f) {
            $this->atomFunctionIs($f)
                 ->outIs('ARGUMENTS')
                 ->isMore('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }

        // this is for custom functions
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->outIs('ARGUMENTS')
             ->savePropertyAs('count', 'args_count')
             ->inIs('ARGUMENTS')
             ->functionDefinition()
             ->analyzerIsNot('Functions/VariableArguments')
             ->outIs('ARGUMENTS')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->outIs('ARGUMENTS')
             ->savePropertyAs('count', 'args_count')
             ->inIs('ARGUMENTS')
             ->functionDefinition()
             ->analyzerIsNot('Functions/VariableArguments')
             ->outIs('ARGUMENTS')
             ->isLess('args_max', 'args_count')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
