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


class CouldBeBoolean extends CouldBeType {
    public function analyze(): void {
        $booleanAtoms = array('Comparison', 'Logical', 'Boolean', 'Not');

        // property : based on default value (created or not)
        $this->checkPropertyDefault($booleanAtoms);

        $this->checkPropertyRelayedDefault($booleanAtoms);

        // property relayed typehint
        $this->checkPropertyRelayedTypehint(array('Scalartypehint'), array('\\bool'));

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Scalartypehint'), array('\\bool'));
        $this->checkPropertyWithPHPCalls('bool');

        // return type
        $this->checkReturnedAtoms($booleanAtoms);

        $this->checkReturnedCalls(array('Scalartypehint'), array('\\bool'));

        $this->checkReturnedPHPTypes('bool');

        $this->checkReturnedDefault($booleanAtoms);

        $this->checkReturnedTypehint(array('Scalartypehint'), array('\\bool'));

        // argument type
        $this->checkArgumentUsage(array('Variablearray'));

        // function ($a = array())
        $this->checkArgumentDefaultValues($booleanAtoms);

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument(array('Scalartypehint'), array('\\bool'));

        // function ($a) { array_diff($a);}
        $this->checkRelayedArgumentToPHP('bool');

        // argument validation
        $this->checkArgumentValidation(array('\\is_bool'), $booleanAtoms);

        // argument because used in a specific operation
        // $arg && ''
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Comparison', 'Logical', 'Not'))
             ->back('result');
        $this->prepareQuery();

        // May also cover if( $arg).,
        // May also cover coalesce, ternary.
        // short assignations
    }
}

?>
