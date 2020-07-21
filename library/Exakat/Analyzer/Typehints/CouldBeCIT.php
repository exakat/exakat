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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;

class CouldBeCIT extends CouldBeType {
    public function analyze() {
        $citOperations = array('New', 'Clone');
        $citAtoms      = array('Identifier', 'Nsname', 'Static', 'Self', 'Parent');

        // property : based on default value (created or not)
        $this->checkPropertyDefault($citOperations);

        $this->checkPropertyRelayedDefault($citOperations);

        // property relayed typehint
        $this->checkPropertyRelayedTypehint($citAtoms, array());

        // property relayed typehint
        $this->checkPropertyWithCalls($citAtoms, array());

        //Not possible ATM
//        $this->checkPropertyWithPHPCalls('bool');

//Cas de syntaxe : utilisé dans des syntaxes objets


        // return type
        $this->checkReturnedAtoms($citOperations);

        $this->checkReturnedCalls($citAtoms, array());

//        $this->checkReturnedPHPTypes('bool');

//        $this->checkReturnedDefault($booleanAtoms);

        $this->checkReturnedTypehint($citAtoms, array());

        // argument type
        // $arg . ''
        $this->checkArgumentUsage(array('Variablearray'));

        // function ($a = array())
        $this->checkArgumentDefaultValues($citOperations);

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument($citAtoms, array());

        // function ($a) { array_diff($a);}
//        $this->checkRelayedArgumentToPHP('Nsname');
//        $this->checkRelayedArgumentToPHP('Identifier');
        
        // argument validation
        $this->checkArgumentValidation(array('\\is_object'), array());

        // $arg instanceof B
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('VARIABLE')
             ->atomIs('Instanceof')
             ->back('result');
        $this->prepareQuery();

        // argument because used in an object or static syntax
        // foo($arg) { $arg->o; }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('OBJECT', 'CLASS'))
             ->atomIs(array('Member', 'Methodcall', 'Staticmethodcall', 'Staticclass', 'Staticproperty', 'Staticconstant'))
             ->back('result');
        $this->prepareQuery();

        // throw $arg  (An exception must be an object)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('THROW')
             ->atomIs('Throw')
             ->back('result');
        $this->prepareQuery();

        // May also cover if( $arg).,
        // May also cover coalesce, ternary.
        // short assignations
    }
}

?>
