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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class CouldBeIterable extends CouldBeType {
    public function analyze() {
        $iterableAtoms = array('Arrayliteral');

        // property relayed typehint
        $this->checkPropertyRelayedTypehint(array('Scalartypehint'), array('\\iterable'));

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Scalartypehint'), array('\\iterable'));

        // foreach($this->p as $b)
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('SOURCE')
             ->atomIs(array('Foreach'))
             ->back('first');
        $this->prepareQuery();

        // yield from $this->p
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('YIELD')
             ->atomIs(array('Yieldfrom'))
             ->back('first');
        $this->prepareQuery();

        // return type
        $this->checkReturnedAtoms($iterableAtoms);
        $this->checkReturnedDefault($iterableAtoms);

        $this->checkReturnedCalls(array('Scalartypehint'), array('\\iterable'));

        $this->checkReturnedTypehint(array('Scalartypehint'), array('\\iterable', '\\array'));

        // function ($a) { bar($a);} function bar(iterable $b) {}
        $this->checkRelayedArgument(array('Scalartypehint'), array('\\iterable'));

        // argument validation
        $this->checkArgumentValidation(array('\\is_iterable'), $iterableAtoms);

        // argument because used in a specific operation
        // foreach($arg as $b)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('SOURCE')
             ->atomIs(array('Foreach'))
             ->back('first');
        $this->prepareQuery();

        // $arg[x]
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('VARIABLE')
             ->atomIs(array('Array'))
             ->back('result');
        $this->prepareQuery();

        // yield from $this->p
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('YIELD')
             ->atomIs(array('Yieldfrom'))
             ->back('result');
        $this->prepareQuery();
        
        // May also cover if( $arg).,
        // May also cover coalesce, ternary.
        // short assignations
    }
}

?>
