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

        // Needs to finish the list of methods and their arguments.
        // Needs to checks on constructors too
        // Refactor this analysis to link closely fullnspath and method name. Currently, it is done by batch

        // Checking PHP functions
        $minArgs = array();
        $maxArgs = array();
        foreach($methods as $method) {
            $ns = $this->dictCode->translate(array($method['class']), self::CASE_INSENSITIVE);
            if (empty($ns)) {
                continue;
            }

            $name = $this->dictCode->translate(array($method['name']), self::CASE_INSENSITIVE);
            if (empty($name)) {
                continue;
            }

            $mfnp = makeFullNSpath($method['class']) . '::' . $name[0];

            if ($method['args_min'] > 0) {
                $minArgs[$mfnp] = $method['args_min'];
            }
            if ($method['args_max'] < \MAX_ARGS) {
                $maxArgs[$mfnp] = $method['args_max'];
            }
        }

        // less argument than the minimum number of arguments
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('CLASS')
                     ->savePropertyAs('fullnspath', 'fnp')
             )

             ->filter(
                $this->side()
                     ->outIs('METHOD')
                     ->outIs('NAME')
                     ->savePropertyAs('lccode', 'name')
             )
             ->initVariable('mfnp', '0')
             ->raw('sideEffect{ mfnp = fnp + "::" + name;}')

             ->outIs('METHOD')
             ->isLessHash('count', $minArgs, 'mfnp')

             ->back('first');
        $this->prepareQuery();

        // more arguments than the minimum number of arguments
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('CLASS')
                     ->savePropertyAs('fullnspath', 'fnp')
             )

             ->filter(
                $this->side()
                     ->outIs('METHOD')
                     ->outIs('NAME')
                     ->savePropertyAs('lccode', 'name')
             )
             ->initVariable('mfnp', '0')
             ->raw('sideEffect{ mfnp = fnp + "::" + name;}')

             ->outIs('METHOD')
             ->isMoreHash('count', $maxArgs, 'mfnp')

             ->back('first');
        $this->prepareQuery();

       // $o->m(), too little arguments
       $this->atomIs('Methodcall')
            ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('OBJECT')
                     ->inIs('DEFINITION')
                     ->atomIs('Variabledefinition')
                     ->outIs('DEFAULT')
                     ->atomIs('New')
                     ->outIs('NEW')
                     ->savePropertyAs('fullnspath', 'fnp')
             )

             ->filter(
                $this->side()
                     ->outIs('METHOD')
                     ->outIs('NAME')
                     ->savePropertyAs('lccode', 'name')
             )
             ->initVariable('mfnp', '0')
             ->raw('sideEffect{ mfnp = fnp + "::" + name;}')

             ->outIs('METHOD')
             ->isLessHash('count', $minArgs, 'mfnp')

            ->back('first');
       $this->prepareQuery();

       // $o->m(), too many arguments
       $this->atomIs('Methodcall')
            ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('OBJECT')
                     ->inIs('DEFINITION')
                     ->atomIs('Variabledefinition')
                     ->outIs('DEFAULT')
                     ->atomIs('New')
                     ->outIs('NEW')
                     ->savePropertyAs('fullnspath', 'fnp')
             )

             ->filter(
                $this->side()
                     ->outIs('METHOD')
                     ->outIs('NAME')
                     ->savePropertyAs('lccode', 'name')
             )
             ->initVariable('mfnp', '0')
             ->raw('sideEffect{ mfnp = fnp + "::" + name;}')

             ->outIs('METHOD')
             ->isMoreHash('count', $maxArgs, 'mfnp')

            ->back('first');
       $this->prepareQuery();

        //Custom methods, when we can find the definition
        $this->atomIs(array('Methodcall', 'Staticmethodcall'))
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->savePropertyAs('count', 'call')
             ->back('first')
             ->inIs('DEFINITION')
             ->atomIs(array('Method', 'Magicmethod'))
             ->analyzerIsNot('Functions/VariableArguments')
             ->isLess('call', 'args_min')
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
