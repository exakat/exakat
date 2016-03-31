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

class WrongNumberOfArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Functions/VariableArguments');
    }
    
    public function analyze() {
        // For Instanciation (needs constructors)
        // In the class itself
       $this->atomIs('Functioncall')
             ->hasIn('NEW')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->classDefinition()
             ->filter(' it.out("BLOCK").out("ELEMENT").out("NAME").has("code", "__construct").any() ')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->analyzerIsNot('Functions/VariableArguments')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

        // In the parent
       $this->atomIs('Functioncall')
             ->hasIn('NEW')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->classDefinition()
             ->filter(' it.out("BLOCK").out("ELEMENT").out("NAME").has("code", "__construct").any() == false')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->analyzerIsNot('Functions/VariableArguments')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

        // In the grand-parent
       $this->atomIs('Functioncall')
             ->hasIn('NEW')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->classDefinition()
             ->filter(' it.out("BLOCK").out("ELEMENT").out("NAME").has("code", "__construct").any() == false')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->filter(' it.out("BLOCK").out("ELEMENT").out("NAME").has("code", "__construct").any() == false')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->analyzerIsNot('Functions/VariableArguments')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

       $this->atomIs('Functioncall')
             ->hasIn('NEW')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->analyzerIsNot('Functions/VariableArguments')
             ->isLess('args_max', 'args_count')
             ->back('first');
        $this->prepareQuery();

        // this is for functions defined within PHP
        $data = new \Data\Methods();
        
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
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->isLess('args_count', $nb);
            $this->prepareQuery();
        }

        foreach($argsMaxs as $nb => $f) {
            $this->atomFunctionIs($f)
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->isMore('args_count', $nb);
            $this->prepareQuery();
        }

        // this is for custom functions
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->functionDefinition()
             ->inIs('NAME')
             ->analyzerIsNot('Functions/VariableArguments')
             ->isMore('args_min', 'args_count')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->savePropertyAs('args_count', 'args_count')
             ->functionDefinition()
             ->inIs('NAME')
             ->analyzerIsNot('Functions/VariableArguments')
             ->isLess('args_max', 'args_count')
             ->back('first');
        $this->prepareQuery();
 
 
    }
}

?>
