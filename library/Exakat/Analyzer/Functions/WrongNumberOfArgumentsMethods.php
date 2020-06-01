<?php declare(strict_types = 1);
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

class WrongNumberOfArgumentsMethods extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                     'Complete/SetClassMethodRemoteDefinition',
                     'Complete/PropagateCalls',
                     'Functions/VariableArguments',
                    );
    }

    public function analyze() {
        $methods = $this->methods->getMethodsArgsInterval();
        $argsMins    = array();
        $argsMaxs    = array();
        $argsMinsFNP = array();
        $argsMaxsFNP = array();

        // Needs to finish the list of methods and their arguments.
        // Needs to checks on constructors too
        // Refactor this analysis to link closely fullnspath and method name. Currently, it is done by batch

        // Checking PHP functions
        foreach($methods as $method) {
            if ($method['args_min'] > 0) {
                array_collect_by($argsMins, $method['args_min'], mb_strtolower($method['name']));
                array_collect_by($argsMinsFNP, $method['args_min'], makeFullNSpath($method['class']));
            }
            if ($method['args_max'] < \MAX_ARGS) {
                array_collect_by($argsMaxs, $method['args_max'], mb_strtolower($method['name']));
                array_collect_by($argsMaxsFNP, $method['args_max'], makeFullNSpath($method['class']));
            }
        }

        // case for methods
        foreach($argsMins as $nb => $f) {
            if (empty($f)) { continue; }

            $this->atomIs('Staticmethodcall')
                 ->analyzerIsNot('self')
                 ->outIs('METHOD')
                 ->codeIs($f, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->isLess('count', $nb)
                 ->inIs('METHOD')
                 ->outIs('CLASS')
                 ->isHash('fullnspath', $argsMinsFNP, (string) $nb)
                 ->inIs('CLASS')
                 ->back('first');
            $this->prepareQuery();

            // Check for type with new assignation
            $this->atomIs('Methodcall')
                 ->analyzerIsNot('self')
                 ->outIs('METHOD')
                 ->codeIs($f)
                 ->isLess('count', $nb)
                 ->inIs('METHOD')
                 ->outIs('OBJECT')
                 ->inIs('DEFINITION')
                 ->atomIs('Variabledefinition')
                 ->outIs('DEFINITION')
                 ->inIs('LEFT')
                 ->atomIs('Assignation')
                 ->codeIs('=')
                 ->outIs('RIGHT')
                 ->atomIs('New')
                 ->outIs('NEW')
                 ->isHash('fullnspath', $argsMinsFNP, (string) $nb)
                 ->back('first');
            $this->prepareQuery();

            // TODO : add support for typehint, member property
        }

        foreach($argsMaxs as $nb => $f) {
            if (empty($f)) { continue; }

            $this->atomIs('Staticmethodcall')
                 ->analyzerIsNot('self')
                 ->outIs('METHOD')
                 ->codeIs($f, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->isMore('count', $nb)
                 ->inIs('METHOD')
                 ->outIs('CLASS')
                 ->isHash('fullnspath', $argsMaxsFNP, (string) $nb)
                 ->inIs('CLASS')
                 ->back('first');
            $this->prepareQuery();

            // Check for type with new assignation
            $this->atomIs('Methodcall')
                 ->analyzerIsNot('self')
                 ->outIs('METHOD')
                 ->codeIs($f)
                 ->isMore('count', $nb)
                 ->inIs('METHOD')
                 ->outIs('OBJECT')
                 ->inIs('DEFINITION')
                 ->atomIs('Variabledefinition')
                 ->outIs('DEFINITION')
                 ->inIs('LEFT')
                 ->atomIs('Assignation')
                 ->codeIs('=')
                 ->outIs('RIGHT')
                 ->atomIs('New')
                 ->outIs('NEW')
                 ->isHash('fullnspath', $argsMaxsFNP, (string) $nb)
                 ->back('first');
            $this->prepareQuery();
        }

        //Custom methods, when we can find the definition
        $this->atomIs(array('Methodcall', 'Staticmethodcall'))
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->savePropertyAs('count', 'call')
             ->back('first')
             ->inIs('DEFINITION')
             ->atomIs(array('Method', 'Magicmethod'))
             ->analyzerIsNot('Functions/VariableArguments')
             ->IsLess('call', 'args_min')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs(array('Methodcall', 'Staticmethodcall'))
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->savePropertyAs('count', 'call')
             ->back('first')
             ->inIs('DEFINITION')
             ->atomIs(array('Method', 'Magicmethod'))
             ->analyzerIsNot('Functions/VariableArguments')
             ->isMore('call', 'args_max')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
